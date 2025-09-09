<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * @OA\Tag(name="Recipes", description="Receitas do usuário")
 */
class RecipeController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/recipes",
     *   tags={"Recipes"}, summary="Listar receitas (busca por q)",
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="q", in="query", required=false, @OA\Schema(type="string")),
     *   @OA\Response(response=200, description="Lista paginada")
     * )
     */
    public function index(Request $r) {
        $q = Recipe::where('user_id', $r->user()->id);
        if ($s = $r->query('q')) {
            $q->where(function($w) use ($s) {
                $w->where('title', 'like', "%$s%")
                    ->orWhere('description','like',"%$s%")
                    ->orWhereJsonContains('tags', $s);
            });
        }
        return response()->json($q->orderByDesc('id')->paginate(10));
    }

    /**
     * @OA\Post(
     *   path="/api/recipes",
     *   tags={"Recipes"}, summary="Criar receita",
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(@OA\JsonContent(
     *     required={"title"},
     *     @OA\Property(property="title", type="string"),
     *     @OA\Property(property="description", type="string"),
     *     @OA\Property(property="ingredients", type="array", @OA\Items(type="string")),
     *     @OA\Property(property="steps", type="array", @OA\Items(type="string")),
     *     @OA\Property(property="prep_time", type="integer"),
     *     @OA\Property(property="yield", type="string"),
     *     @OA\Property(property="tags", type="array", @OA\Items(type="string"))
     *   )),
     *   @OA\Response(response=201, description="Criado")
     * )
     */
    public function store(StoreRecipeRequest $r) {
        $data = $r->validated();
        $data['user_id'] = $r->user()->id;
        $recipe = Recipe::create($data);
        return response()->json(['data'=>$recipe], 201);
    }

    /**
     * @OA\Get(path="/api/recipes/{uuid}", tags={"Recipes"},
     *   summary="Detalhe", security={{"sanctum":{}}},
     *   @OA\Parameter(name="uuid", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function show(Request $r, string $uuid) {
        $recipe = Recipe::where('uuid',$uuid)->where('user_id',$r->user()->id)->firstOrFail();
        return response()->json(['data'=>$recipe]);
    }

    /**
     * @OA\Put(path="/api/recipes/{uuid}", tags={"Recipes"},
     *   summary="Atualizar", security={{"sanctum":{}}},
     *   @OA\Parameter(name="uuid", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function update(UpdateRecipeRequest $r, string $uuid) {
        $recipe = Recipe::where('uuid',$uuid)->where('user_id',$r->user()->id)->firstOrFail();
        $recipe->update($r->validated());
        return response()->json(['data'=>$recipe->refresh()]);
    }

    /**
     * @OA\Delete(path="/api/recipes/{uuid}", tags={"Recipes"},
     *   summary="Excluir", security={{"sanctum":{}}},
     *   @OA\Parameter(name="uuid", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(response=204, description="Removido")
     * )
     */
    public function destroy(Request $r, string $uuid) {
        $recipe = Recipe::where('uuid',$uuid)->where('user_id',$r->user()->id)->firstOrFail();
        $recipe->delete();
        return response()->noContent();
    }

    /**
     * @OA\Get(path="/api/recipes/{uuid}/print", tags={"Recipes"},
     *   summary="Imprimir (PDF)", security={{"sanctum":{}}},
     *   @OA\Parameter(name="uuid", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(response=200, description="PDF")
     * )
     */
    public function print(Request $r, string $uuid) {
        $recipe = Recipe::where('uuid',$uuid)->firstOrFail();
        $pdf = Pdf::loadView('recipes.print', ['recipe'=>$recipe]);
        return $pdf->stream("receita-{$recipe->uuid}.pdf");
    }
}
