<?php

namespace backend\controllers;

use yii\web\Controller;
use common\models\Jogo;

class QuizController extends Controller
{
    public function actionIndex()
    {
        $query = Jogo::find()->with('autor');

        $filtroOrdenar = \Yii::$app->request->get('filtroOrdenar');

        switch ($filtroOrdenar) {
            case 'publicos_desc':
                $query->andWhere(['IsPublic' => 1])->orderBy(['datacriacao' => SORT_DESC]);
                break;
            case 'publicos_asc':
                $query->andWhere(['IsPublic' => 1])->orderBy(['datacriacao' => SORT_ASC]);
                break;
            case 'privados_desc':
                $query->andWhere(['IsPublic' => 0])->orderBy(['datacriacao' => SORT_DESC]);
                break;
            case 'privados_asc':
                $query->andWhere(['IsPublic' => 0])->orderBy(['datacriacao' => SORT_ASC]);
                break;
            case 'todos_desc':
                $query->orderBy(['datacriacao' => SORT_DESC]);
                break;
            case 'todos_asc':
                $query->orderBy(['datacriacao' => SORT_ASC]);
                break;
            default:
                // Nenhum filtro - ordem padrão (podes mudar se quiser)
                break;
        }

        $jogos = $query->all();

        return $this->render('index', [
            'jogos' => $jogos,
            'filtroOrdenar' => $filtroOrdenar,
        ]);
    }

    public function actionView($id)
    {
        $jogo = Jogo::find()->with('perguntas', 'autor')->where(['id' => $id])->one();

        if (!$jogo) {
            throw new \yii\web\NotFoundHttpException('Jogo não encontrado.');
        }

        return $this->render('view', [
            'jogo' => $jogo,
        ]);
    }

    public function actionCreatePergunta($jogo_id)
    {
        $model = new \common\models\Pergunta();
        $model->jogo_id = $jogo_id;

        // Calcula o número da próxima pergunta
        $nextNumber = \common\models\Pergunta::find()->where(['jogo_id' => $jogo_id])->count() + 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Pergunta criada com sucesso!');
            return $this->redirect(['view', 'id' => $jogo_id]); // redireciona para a página do quiz
        }

        return $this->render('createPergunta', [
            'model' => $model,
            'jogo_id' => $jogo_id,
            'nextNumber' => $nextNumber,
        ]);
    }


}
