<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/17
 * Time: 19:55
 */

namespace app\modules\v2\advertDept\rest;


use app\common\rest\AdminBaseController;
use app\modules\v2\advertDept\domain\aggregate\ProductLibraryAggregate;
use app\modules\v2\advertDept\domain\dto\ProductLibraryDto;
use app\modules\v2\advertDept\domain\dto\ProductLibraryForm;
use Exception;
use yii\base\Model;

class ProductLibraryController extends AdminBaseController
{
    public $dto;

    /** @var ProductLibraryAggregate */
    public $productLibraryAggregate;
    /** @var ProductLibraryDto */
    public $productLibraryDto;
    /** @var ProductLibraryForm */
    public $productLibraryForm;

    public function __construct($id, $module,
                                ProductLibraryAggregate $productLibraryAggregate,
                                ProductLibraryForm $productLibraryForm,
                                ProductLibraryDto $productLibraryDto,
                                $config = [])
    {
        $this->productLibraryAggregate = $productLibraryAggregate;
        $this->productLibraryForm = $productLibraryForm;
        $this->productLibraryDto = $productLibraryDto;
        parent::__construct($id, $module, $config);
    }


    public function verbs():array
    {
        return [
            'index'  => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'read'   => ['GET', 'HEAD', 'OPTIONS'],
            'update' => ['POST', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }

    /**
     * @param string $actionName
     * @return Model
     * @throws Exception
     * author: pengguochao
     * Date Time 2019/10/18 9:10
     */
    public function dtoMap(string $actionName): Model
    {
        switch ($actionName){
            case 'actionIndex':
                return $this->productLibraryDto->setScenario(ProductLibraryDto::SEARCH);
            case 'actionCreate':
                return $this->productLibraryForm;
            case 'actionRead':
                return $this->productLibraryDto->setScenario(ProductLibraryDto::READ);
            case 'actionUpdate':
                return $this->productLibraryForm->setScenario(ProductLibraryForm::UPDATE);
            case 'actionDelete':
                return $this->productLibraryDto->setScenario(ProductLibraryDto::DELETE);
            default:
                throw new Exception('unKnow actionName ');
        }
    }

    /**
     * 查询产品库数据
     * @return array
     * author: pengguochao
     * Date Time 2019/10/18 9:12
     */
    public function actionIndex(): array
    {
        $data = $this->productLibraryAggregate->listProductLibrary($this->productLibraryDto);
        return ['成功返回数据', 200, $data];
    }

    /**
     * 创建一个产品库
     * @return array
     * author: pengguochao
     * Date Time 2019/10/18 9:18
     */
    public function actionCreate(): array
    {
        try {
            $this->productLibraryAggregate->createProductLibrary($this->productLibraryForm);
            return ['添加产品库成功', 200, []];
        } catch (Exception $e) {
            return [$e->getMessage(), 500, []];
        }
    }

    /**
     * 获取产品库详情
     * @return array
     * author: pengguochao
     * Date Time 2019/10/18 9:20
     */
    public function actionRead(): array
    {
        try {
            $data = $this->productLibraryAggregate->detailProductLibrary($this->productLibraryDto);
            return ['查看详情成功', 200, $data];
        } catch (Exception $e) {
            return [$e->getMessage(), 500, []];
        }
    }

    /**
     * 更新产品库
     * @return array
     * author: pengguochao
     * Date Time 2019/10/18 9:22
     */
    public function actionUpdate(): array
    {
        try {
            $this->productLibraryAggregate->updateProductLibrary($this->productLibraryForm);
            return ['更新产品库成功', 200, []];
        } catch (Exception $e) {
            return [$e->getMessage(), 500, []];
        }
    }

    /**
     * 删除产品库
     * @return array
     * author: pengguochao
     * Date Time 2019/10/18 9:25
     */
    public function actionDelete(): array
    {
        try {
            $this->productLibraryAggregate->deleteProductLibrary($this->productLibraryDto);
            return ['删除产品库成功', 200, []];
        } catch (Exception $e) {
            return [$e->getMessage(), 500, []];
        }
    }
}