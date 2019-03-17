<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 16/03/19
 * Time: 16:40
 */

namespace App\Http\Controllers\Challenge;


use App\Entities\Lesson\Challenge\Challenge;
use App\Entities\Lesson\Lesson;
use App\FieldManagers\Challenge\ChallengeFieldManager;
use App\Http\Controllers\ApiController;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ChallengeController extends ApiController
{
    use ApiResponse;

    public function __construct(Challenge $model, ChallengeFieldManager $fieldManager)
    {
        $this->fieldManager = $fieldManager;
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     path="/sections/{section_id}/lessons/{lesson_id}/challenges",
     *     summary="Lista todos os desafios",
     *     operationId="GetChallenges",
     *     tags={"challenges"},
     *     @OA\Parameter(
     *         name="section_id",
     *         in="path",
     *         description="Id da sessão",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lesson_id",
     *         in="path",
     *         description="Id da lição",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="includes",
     *         in="query",
     *         description="Faz o include das relações",
     *         required=false,
     *         @OA\Schema(
     *           type="array",
     *           @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nome do desafio",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Descrição do desafio",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     * )
     */
    public function getChallenges(Request $request, $section_id, $lesson_id)
    {
        $request->merge(['lesson_id' => $lesson_id]);
        $lesson = Lesson::where([
            ['id', '=', $lesson_id],
            ['section_id', '=', $section_id],
        ])->first();

        if(!$lesson){
            return $this->notFound(['error' => 'This lesson doesnt exists or you are at the wrong section.']);
        }

        return parent::index($request); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Post(
     *     path="/sections/{section_id}/lessons/{lesson_id}/challenges",
     *     summary="Cria um novo desafio",
     *     operationId="PostChallenge",
     *     tags={"challenges"},
     *     @OA\Parameter(
     *         name="section_id",
     *         in="path",
     *         description="Id da sessão",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lesson_id",
     *         in="path",
     *         description="Id da lição",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nome do desafio",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Descrição do desafio",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     * )
     */
    public function postChallenge(Request $request, $section_id, $lesson_id)
    {
        $request->merge(['lesson_id' => $lesson_id]);
        $lesson = Lesson::where([
            ['id', '=', $lesson_id],
            ['section_id', '=', $section_id],
        ])->first();

        if(!$lesson){
            return $this->notFound(['error' => 'This lesson doesnt exists or you are at the wrong section.']);
        }

        return parent::store($request); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Get(
     *     path="/sections/{section_id}/lessons/{lesson_id}/challenges/{challenge_id}",
     *     summary="Lista um desafio",
     *     operationId="GetChallenge",
     *     tags={"challenges"},
     *     @OA\Parameter(
     *         name="section_id",
     *         in="path",
     *         description="Id da sessão",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lesson_id",
     *         in="path",
     *         description="Id da lição",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="challenge_id",
     *         in="path",
     *         description="Id do desafio",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     * )
     */
    public function getChallenge(int $section_id, int $lesson_id, $challenge_id)
    {
        $challenge = Challenge::select(['lesson_challenges.id','lesson_challenges.name', 'description'])
            ->join('lessons', 'lessons.id', '=', 'lesson_challenges.lesson_id')
            ->where([
                ['lesson_challenges.id', '=', $challenge_id],
                ['lessons.section_id', '=', $section_id],
                ['lesson_challenges.lesson_id', '=', $lesson_id]
            ])->first();

        if(!$challenge){
            return $this->notFound(['error' => 'This challenge doesnt exists or you are at the wrong lesson.']);
        }
        return parent::show($challenge_id); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Put(
     *     path="/sections/{section_id}/lessons/{lesson_id}/challenges/{challenge_id}",
     *     summary="Lista um desafio",
     *     operationId="UpdateChallenge",
     *     tags={"challenges"},
     *     @OA\Parameter(
     *         name="section_id",
     *         in="path",
     *         description="Id da sessão",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lesson_id",
     *         in="path",
     *         description="Id da lição",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="challenge_id",
     *         in="path",
     *         description="Id do desafio",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nome do desafio",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Descrição do desafio",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     * )
     */
    public function putChallenge(Request $request, int $section_id, int $lesson_id, int $challenge_id)
    {
        $request->merge(['lesson_id' => $lesson_id]);
        $challenge = Challenge::select(['lesson_challenges.id','lesson_challenges.name', 'description'])
            ->join('lessons', 'lessons.id', '=', 'lesson_challenges.lesson_id')
            ->where([
                ['lesson_challenges.id', '=', $challenge_id],
                ['lessons.section_id', '=', $section_id],
                ['lesson_challenges.lesson_id', '=', $lesson_id]
            ])->first();

        if(!$challenge){
            return $this->notFound(['error' => 'This challenge doesnt exists or you are at the wrong lesson.']);
        }
        return parent::update($request, $challenge_id); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Delete(
     *     path="/sections/{section_id}/lessons/{lesson_id}/challenges/{challenge_id}",
     *     summary="Lista um desafio",
     *     operationId="DeleteChallenge",
     *     tags={"challenges"},
     *     @OA\Parameter(
     *         name="section_id",
     *         in="path",
     *         description="Id da sessão",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lesson_id",
     *         in="path",
     *         description="Id da lição",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="challenge_id",
     *         in="path",
     *         description="Id do desafio",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     )
     * )
     */
    public function deleteChallenge(int $section_id, int $lesson_id, int $challenge_id)
    {
        $challenge = Challenge::select(['lesson_challenges.id','lesson_challenges.name', 'description'])
            ->join('lessons', 'lessons.id', '=', 'lesson_challenges.lesson_id')
            ->where([
                ['lesson_challenges.id', '=', $challenge_id],
                ['lessons.section_id', '=', $section_id],
                ['lesson_challenges.lesson_id', '=', $lesson_id]
            ])->first();

        if(!$challenge){
            return $this->notFound(['error' => 'This challenge doesnt exists or you are at the wrong lesson.']);
        }
        return parent::destroy($challenge_id); // TODO: Change the autogenerated stub
    }
}