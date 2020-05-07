<?php


namespace core;


use Exception;

class Model
{
    public $id;

    /**
     * @var null|array
     */
    private $errors;

    public function getName()
    {
        return str_replace('\\', '_', static::class);
    }

    private function getFileName()
    {
        return dirname(__DIR__) . '/app/store/' . $this->getName() . '.json';
    }

    private function readAllData()
    {
        $name = $this->getFileName();

        if (!file_exists($name)) {
            touch($name);
            return [];
        }

        if ($content = file_get_contents($name)) {
            return json_decode($content, true);
        }
        return [];
    }

    private function getRecordById($id)
    {
        if (!$data = $this->readAllData()) {
            return null;
        }

        return Helper::getValue($data, $id);
    }
    
    public function getById($id)
    {
        if (!$record = $this->getRecordById($id)) {
            return null;
        }

        $model = new static();
        $model->id = $id;
        $model->load($record);
        
        return $model;
    }

    public function sort()
    {
        [$field, $direction] = Helper::getSortParam();
        if (!$field) {
            return $this->all();
        }
        $attributes = $this->attributes();
        $attributes[] = 'id';
        if (!in_array($field, $attributes, true)) {
            throw new Exception('Wrong sort parameter');
        }
        if (!in_array($direction, [SORT_DESC, SORT_ASC], true)) {
            throw new Exception('Wrong sort direction');
        }

        $data = $this->readAllData();

        usort($data, static function ($a, $b) {
            $result = strcasecmp($a, $b);
        });

        return $direction === SORT_ASC
            ? $data
            : array_reverse($data);
    }

    public function all()
    {
        return $this->readAllData();
    }

    public function update()
    {
        if (!$data = $this->getRecordById($this->id)) {
            return false;
        }

        foreach ($this->attributes() as $field) {
            $data[$field] = $this->$field;
        }

        $all = $this->readAllData();
        $all[$this->id] = $data;

        return $this->store($all);
    }
    
    public function add()
    {
        $id = time();
        $data = [];
        $data['id'] = $id;
        foreach ($this->attributes() as $field) {
            $data[$field] = $this->$field;
        }

        $all = $this->readAllData();
        $all[$id] = $data;
        
        return $this->store($all);
    }
    
    private function store($data)
    {
        $name = $this->getFileName();
        if (!file_exists($name)) {
            touch($name);
        }
        
        $json = json_encode($data);
        return file_put_contents($name, $json);
    }
    
    public function rules()
    {
        return [];
    }
    
    public function validate()
    {
        $this->setErrors(null);

        foreach ($this->rules() as $field => $rules) {
            if (!property_exists($this, $field)) {
                throw new Exception("Field {$field} don't found");
            }

            foreach ($rules as $rule) {
                $method = Helper::getValue($rule, 'method');
                if (!$method) {
                    throw new Exception('Validate method is required');
                }

                if ($method === 'required') {
                    if (!$this->$field) {
                        $this->addError($field, Helper::getValue($rule, 'message', "Field {$field} is required"));
                    }
                    continue;
                }

                $callback = Helper::getValue($rule, 'callback');
                if ($method === FILTER_CALLBACK) {
                    if (!$callback || !is_callable($callback)) {
                        throw new Exception('Validate callback is required for FILTER_CALLBACK method');
                    }
                    $this->$field = filter_var($this->$field, $method, ['options' => $callback]);
                    continue;
                }

                if ($method === FILTER_VALIDATE_BOOLEAN) {
                    $this->$field = filter_var($this->$field, $method);
                    continue;
                }

                if (!$val = filter_var($this->$field, $method)) {
                    $this->addError($field, Helper::getValue($rule, 'message', "Field {$field} validation failed"));
                }
                $this->$field = $val;
            }
        }

        return !$this->hasErrors();
    }

    public function hasErrors()
    {
        return (bool)$this->errors;
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param array|null $errors
     */
    public function setErrors(?array $errors): void
    {
        $this->errors = $errors;
    }

    public function addError($field, $error)
    {
        if (!array_key_exists($field, $this->errors)) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $error;
    }

    public function getFirstError($field = null)
    {
        if ($field) {
            return Helper::getValue($this->errors, $field);
        }
        if (!$errors = $this->getErrors()) {
            return '';
        }
        $errors = Helper::getValue(array_values($errors), 0);
        return Helper::getValue($errors, 0);
    }

    public function load($data)
    {
        foreach ($this->attributes() as $field) {
            if (!property_exists($this, $field)) {
                continue;
            }
            $this->$field = Helper::getValue($data, $field);
        }
    }

    public function attributes()
    {
        return array_keys($this->rules());
    }
}
