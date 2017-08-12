<?php

namespace App\Http\Requests\Mutators;

use Illuminate\Http\Request;

class GroupInputData
{
    private $request;

    /**
     * Fire mutators if there are options for them.
     */
    public function handle($request, $options)
    {
        $this->request = $request;

        if (isset($options['group_inputs']) && $options['group_inputs']) {
             foreach ($options['group_inputs'] as $group_key => $inputs) {
                 $this->groupInputData($group_key, $inputs);

                 if (isset($options['truncate_empty_groups']) && $options['truncate_empty_groups']) {
                     $this->truncateEmptyGroups($group_key, true);
                 }
             }
        }

        return $this->request;
    }

    /**
     * Takes a list of request input variables and groups to request via index.
     *
     * eg. Inputs brand[], cost[] could become:
     * $items[0]['brand'], $items[0]['cost'] etc.
     *
     * @param string $group_key
     * @param array ...$inputs
     *
     * @return array
     */
    public function groupInputData($group_key, ...$inputs)
    {
        $group = [];

        foreach ($inputs[0] as $input) {
            $input_array = $this->request->get($input);

            for ($i = 0; $i < count($input_array); $i++) {
                $group[$i][$input] = trim($input_array[$i]);
            }
        }

        $this->request->set($group_key, $group);
    }

    /**
     * Truncate any empty input groups.
     *
     * @param string $group_key
     * @param boolean $zero_is_empty
     */
    public function truncateEmptyGroups($group_key, $zero_is_empty = true)
    {
        foreach ($this->request->get($group_key) as $index => $group) {
            $empty = 0;

            foreach ($group as $value) {
                if ($zero_is_empty) {
                    $value = $this->trimZeros($value);
                }

                $value = trim($value);

                if (empty($value)) {
                    $empty++;
                }
            }

            if ($empty === 3) {
                $data = $this->request->get($group_key);
                unset($data[$index]);
                $this->request->set($group_key, $data);
            }
        }
    }

    /**
     * Trim zeros from string including currency symbols.
     *
     * @param string $value
     * @return string
     */
    protected function trimZeros($value)
    {
        $value = ltrim($value, '£$ 0');
        $value = preg_replace('/[0]{0,}\.[0]{0,}/', '', $value);

        return $value;
    }
}