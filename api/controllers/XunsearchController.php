<?php

namespace api\controllers;

use api\models\Demo;
use XS;
use XSDocument;
use Yii;
use api\models\AccessToken;
use api\models\AccessTokenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessTokenController implements the CRUD actions for AccessToken model.
 */
class XunsearchController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','get'],
                ],
            ],
        ];
    }

    /**
     * Lists all AccessToken models.
     * @return mixed
     */
    public function actionIndex()
    {
        $xs = new XS('demo'); // 建立 XS 对象，项目名称为：demo
        $search = $xs->search; // 获取 搜索对象
        $index = $xs->index;

        $query = '测试'; // 这里的搜索语句很简单，就一个短语
        $search->setQuery($query); // 设置搜索语句
        //$search->addWeight('subject', 'xunsearch'); // 增加附加条件：提升标题中包含 'xunsearch' 的记录的权重
        //$search->setLimit(5, 10); // 设置返回结果最多为 5 条，并跳过前 10 条

        $docs = $search->search(); // 执行搜索，将搜索结果文档保存在 $docs 数组中
        $count = $search->count(); // 获取搜索结果的匹配总数估算值


        print_r2($docs,$count);

    }

    /**
     * Displays a single AccessToken model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Demo::findOne($id);
        $model->docid(); //Xapian数据 ID
        $model->rank(); //序号
        $model->percent(); //匹配百分比
        $model->ccount(); //折叠数量，须在 XSSearch::setCollapse() 指定后才有效
        //$model->matched(); //获得匹配词汇

        print_r2($model);
    }

    /**
     * Creates a new AccessToken model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $db = \Yii::$app->xunsearch->getDatabase('demo');
        //$db = (\Yii::$app->xunsearch)('demo');
        $xs = $db->xs;
        $search = $db->getSearch();
        $index = $db->getIndex();

        $data = array(
            'pid' => rand(1,10), // 此字段为主键，必须指定
            'subject' => '测试文档的标题',
            'message' => '测试文档的内容部分',
            'chrono' => time()
        );

        // 创建文档对象
        $doc = new XSDocument;
        $doc->setFields($data);

        // 添加到索引数据库中
        $index->add($doc);

        $index->flushIndex();


        //---
        $xs = new XS('demo'); // 建立 XS 对象，项目名称为：demo
        $search = $xs->search; // 获取 搜索对象

        $query = '测试'; // 这里的搜索语句很简单，就一个短语

        $search->setQuery($query); // 设置搜索语句
        //$search->addWeight('subject', 'xunsearch'); // 增加附加条件：提升标题中包含 'xunsearch' 的记录的权重
        //$search->setLimit(5, 10); // 设置返回结果最多为 5 条，并跳过前 10 条

        $docs = $search->search(); // 执行搜索，将搜索结果文档保存在 $docs 数组中
        $count = $search->count(); // 获取搜索结果的匹配总数估算值

        print_r2($docs,$count);
    }

    /**
     * Updates an existing AccessToken model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionCreate2($id=null)
    {

        $db = Demo::getDb();
        $search = $db->getSearch();
        $index = $db->getIndex();
        $scws = $db->getScws();

        // 添加索引，也可以通过 $model->setAttributes([...]) 批量赋值
        $model = new Demo();
        $model->pid = rand(1,10);
        $model->subject = '测试 hello world';
        $model->message = 'just for testing...';
        if(!$model->save())
            print_r2($model->errors);

        $index->flushIndex();
    }

    /**
     * Deletes an existing AccessToken model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id=null)
    {
        //---
        $xs = new XS('demo'); // 建立 XS 对象，项目名称为：demo
        $search = $xs->search; // 获取 搜索对象
        $index= $xs->index; // 设置
        $index->clean();
        $index->flushIndex();

    }

    /**
     * Finds the AccessToken model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AccessToken the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {


    }
}
