<?php
namespace app\widgets;

use Yii;
use yii\helpers\Html;
use yii\base\Model;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class SwitchButton extends \yii\bootstrap4\InputWidget
{
    public $template;

    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;

    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;

    /**
     * @var array HTML attributes for the container (applicable only if `type` = 2)
     */
    public $containerOptions = [];

    /**
     * @var boolean whether label is aligned on same line. Defaults to `true`. If set to `false`, the label and input
     *     will be on separate lines.
     */
    public $inlineLabel = true;

    /**
     * @var boolean whether to enable third indeterminate behavior when type is `SwitchInput::CHECKBOX`. Defaults to
     *     `false`.
     */
    public $tristate = false;

    /**
     * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
     */
    public $name;
    
    public function init()
    {
        if ($this->name === null && !$this->hasModel()) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
        parent::init();
    }

    public function run()
    {
        //     <div class="custom-control custom-switch">
        //     <input type="checkbox" class="custom-control-input" id="customSwitch1">
        //     <label class="custom-control-label" for="customSwitch1">Status</label>
        // </div>

        $html = '<div class="custom-control custom-switch">';
        $html .= '<input type="checkbox" class="custom-control-input" id="customSwitch1">';
        $html .= '<label class="custom-control-label" for="customSwitch1">Status</label>';
        $html .= '</div>';

        // var_dump($this->options); die();
        if ($this->hasModel()) {
            return Html::activeCheckbox($this->model, $this->attribute, [
                'checkTemplate' => '<div class="custom-control custom-switch">{input}{label}</div>'
            ]);
            // return $html;
        }

        // echo $this->renderSwitch();

        // return Html::checkbox('agree', true, ['label' => 'I agree']);
    }

    protected function renderSwitch()
    {
        $input = $this->getInput('checkbox');
        $output = ($this->inlineLabel) ? $input : Html::tag('div', $input);
        $output = $this->mergeIndToggle($output);
        Html::addCssClass($this->containerOptions, 'custom-control custom-switch');
        return Html::tag('div', $output, $this->containerOptions) . "\n";
        // return Html::tag('div', $output, $this->containerOptions) . "\n";
    }

    /**
     * Merges the rendered indeterminate toggle indicator
     *
     * @var string $output the content to merge with the output
     * @return string
     */
    protected function mergeIndToggle($output)
    {
        if (!$this->tristate || $this->indeterminateToggle === false) {
            return $output;
        }

        $icon = ArrayHelper::remove($this->indeterminateToggle, 'label', '&times;');
        $this->indeterminateToggle['data-kv-switch'] = $this->options['id'];
        Html::addCssClass($this->indeterminateToggle, 'close kv-ind-toggle');
        $icon = Html::tag('span', $icon, $this->indeterminateToggle);
        $options = ArrayHelper::remove($this->indeterminateToggle, 'containerOptions', []);
        $size = 'kv-size-' . ArrayHelper::getValue($this->pluginOptions, 'size', 'normal');
        Html::addCssClass($options, 'kv-ind-container ' . $size);
        return Html::tag('div', $icon . "\n" . $output, $options);
    }

    /**
     * Generates an input.
     *
     * @param string $type the input type
     * @param boolean $list whether the input is of dropdown list type
     *
     * @return string the rendered input markup
     */
    protected function getInput($type, $list = false)
    {
        if ($this->hasModel()) {
            $input = 'active' . ucfirst($type);
            return $list ?
                Html::$input($this->model, $this->attribute, $this->data, $this->options) :
                Html::$input($this->model, $this->attribute, $this->options);
        }
        $input = $type;
        $checked = false;
        if ($type == 'radio' || $type == 'checkbox') {
            $checked = ArrayHelper::remove($this->options, 'checked', '');
            if (empty($checked) && !empty($this->value)) {
                $checked = ($this->value == 0) ? false : true;
            } elseif (empty($checked)) {
                $checked = false;
            }
        }
        var_dump($input); die();
        return $list ?
            Html::$input($this->name, $this->value, $this->data, $this->options) : (($type == 'checkbox' || $type == 'radio') ?
                Html::$input($this->name, $checked, $this->options) :
                Html::$input($this->name, $this->value, $this->options));
    }

    /**
     * @return bool whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model && $this->attribute !== null;
    }
}