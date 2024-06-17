<?php
namespace MicroweberPackages\CustomField\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

class CustomField extends Model
{
    //use MaxPositionTrait;
    use CacheableQueryBuilderTrait;
    use HasCreatedByFieldsTrait;
   /// use HasMultilanguageTrait;

    protected $fillable = [
        'rel_id',
        'rel_type',
        'type',
        'options',
        'name',
        'name_key',
        'value',
        // 'created_by'
    ];

    protected $table = 'custom_fields';
    public $timestamps = true;

    public $translatable = ['name', 'placeholder','error_text'];

    public $cacheTagsToClear = ['repositories','content'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    public $casts = [
        'options' => 'json',
    ];

    protected $attributes = [
        'is_active' => 1,
    ];

    protected static string $relType = '';
    protected static string $relId = '';

    public static function queryForRelTypeRelId(string $relType = '',string $relId = ''): Builder
    {
        static::$relType = $relType;
        static::$relId = $relId;

        $query = static::query();
        if (static::$relType) {
            $query->where('rel_type', static::$relType);
        }
        if (static::$relId) {
            $query->where('rel_id', static::$relId);
        }

        return $query;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $relType = $model::$relType;
            $relId = $model::$relId;
            if (!empty($relType)) {
                $model->rel_type = $relType;
            }
            if (!empty($relId)) {
                $model->rel_id = $relId;
            }

        });
    }

    public function fieldValue()
    {
        return $this->hasMany(CustomFieldValue::class, 'custom_field_id', 'id')
            ->orderBy('position');
    }

    public function fieldValueSingle()
    {
        return $this->hasOne(CustomFieldValue::class, 'custom_field_id', 'id');
    }

    public function fieldValueText()
    {
        $fieldValues = $this->fieldValue()->pluck('value');
        if ($fieldValues->count() == 1) {
            return $fieldValues->first();
        }

        return implode(', ', $fieldValues->toArray());
    }

    public function fieldValuePrice()
    {
        return $this->hasMany(CustomFieldValue::class, 'custom_field_id', 'id')

            ->where('type','price')
            ->orderBy('position');
    }

    public function save(array $options = [])
    {
        $customFieldValueToSave = null;

        if (isset($this->value)) {
            $customFieldValueToSave = $this->value;
            unset($this->value);
        }

        if (isset($this->options) and is_string($this->options) and $this->options != '') {
            $this->options = @json_decode($this->options, true);
        }


        if(isset($this->name)) {
            $this->name_key = \Str::slug($this->name, '-');
        }

        if ($this->rel_id < 1) {
            $this->session_id = app()->user_manager->session_id();
        }

        $saved = parent::save($options);

        if (isset($customFieldValueToSave)) {
            if (is_array($customFieldValueToSave)) {
                foreach ($customFieldValueToSave as $val) {
                    $this->createCustomFieldValue($this->id, $val);
                }
            } else {
                $this->createCustomFieldValue($this->id, $customFieldValueToSave);
            }
        }

        return $saved;
     }

     private function createCustomFieldValue($customFieldId, $val){

        $findValue = CustomFieldValue::where('custom_field_id', $customFieldId)->first();
        if (!$findValue) {
            $findValue = new CustomFieldValue();
        }

        $findValue->custom_field_id = $customFieldId;
        $findValue->value = $val;
        $findValue->save();
     }
}
