<?php
namespace common\components;

use Yii;
use yii\base\Model;

class SetupForm extends Model
{
    public $configPath = '@common/config/params-setup.php';
    private $_params = [];

    public $books_limit = null;
    public $feedback_email = '';
    public $source_url = '';

    public function init()
    {
        $this->loadConfig();

        return parent::init();
    }

    public function rules()
    {
        return [[['books_limit', 'feedback_email', 'source_url'], 'safe']];
    }

    public function loadConfig()
    {
        $json = '';
        if (is_file(Yii::getAlias($this->configPath)))
            $json = trim(file_get_contents(Yii::getAlias($this->configPath)));

        if ($json)
            $this->_params = json_decode($json, true);

        $this->load(['SetupForm' => $this->_params]);
    }

    public function save()
    {
        return file_put_contents(Yii::getAlias($this->configPath), json_encode($this->attributes));
    }
}