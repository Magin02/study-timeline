<?php

namespace App\Http\Controllers\achievement;

use App\achievement\Achievement;
use App\Http\Controllers\Controller;
use App\Http\Requests\achievement\AchievementStoreRequest;
use App\Rules\OutOfDate;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AchievementController extends Controller
{
    private  $achievement;

    public function __construct(Achievement $achievement)
    {
        $this->achievement = $achievement;
        $this->middleware('auth');
    }


    public function store(AchievementStoreRequest $request){
        try {
            $validated = $request->validated();
            if (auth()->user()->id !== $request->user_id)
                return response()->json(['Sem autorizaç�o' => 'Aç�o n�o autorizada'],422);

            $achievement = $this->achievement->create($validated);
            $message = 'Realização registrada com sucesso!';
            return response()->json(compact('achievement', 'message'), 201);
        }catch (ValidationException $e){
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e){
            $message = $e->getMessage();
            return response()->json(compact('message'), 500);
        }
    }

    public function update(AchievementStoreRequest $request, int $id){
        try {

            $achievement = $this->achievement->findOrFail($id);
            $this->validate(new Request(['date' => $achievement->date]), [
               'date' => new OutOfDate()
            ]);

            $validated = $request->validated();

            if (auth()->user()->id !== $achievement->user_id)
                return response()->json(['Sem autorizaç�o' => 'Aç�o n�o autorizada'],422);

            $achievement->update($validated);
            $message = 'Realização atualizada com sucesso!';
            return response()->json(compact('achievement', 'message'), 200);
        } catch (ValidationException $e){
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e){
            $message = $e->getMessage();
            return response()->json(compact('message'), 500);
        }
    }

    public function destroy(int $id){

        try {
            $achievement = $this->achievement->findOrFail($id);

            if (auth()->user()->id !== $achievement->user_id)
                return response()->json(['Sem autorizaç�o' => 'Aç�o n�o autorizada'],422);


            if ($achievement->delete()) {
                return response()->json(['message' => 'Realização excluida'], 200);
            }
            return response()->json(['message' => 'Erro ao deletar realização'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar realização'], 500);
        }
    }

    public function show(int $id){

    }


}
