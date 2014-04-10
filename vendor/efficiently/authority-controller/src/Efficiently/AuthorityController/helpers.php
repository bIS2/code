<?php

if (! function_exists('array_extract_options')) {
    function array_extract_options(&$array)
    {
        $options = [];
        if (is_array($array)) {
            $clonedArray = $array;
            uksort($clonedArray, 'strnatcmp');
            foreach ( $clonedArray as $key => $value ) {
                if ( is_string($key) ) {
                    $options[$key] = $value;
                    unset($array[$key]);
                }
            }
        }
        return $options;
    }
}
if (! function_exists('get_classname')) {

    /**
     * Like get_class() function but compatible with Mockery and $object parameter is required
     *
     * @param  object|\Mockery\MockInterface $object
     * @return string
     */
    function get_classname($object)
    {
        return $object instanceof \Mockery\MockInterface ? $object->mockery_getName() : get_class($object);
    }
}

if (! function_exists('respond_to')) {

    /**
     * Like method_exists function but compatible with Mockery
     *
     * @param  mixed   $object
     * @param  string  $methodName
     * @return boolean
     */
    function respond_to($object, $methodName)
    {
        if(method_exists($object, $methodName)) {
            return true;
        } elseif (is_a($object, '\Mockery\MockInterface') && ($expectationDirector = array_get($object->mockery_getExpectations(), $methodName))) {
            foreach ((array) $expectationDirector->getExpectations() as $expectation) {
                if ($expectation->isEligible()) {
                    return true;
                }
            }
        } elseif (is_string($object) && class_exists($object) && is_a(($instance=\App::make($object)), '\Mockery\MockInterface')) {
            // Check if a mocked static method exists or not. You need to do:
            //
            //   $category = Mockery::mock('alias:Category', ['getProducts'=>'products']);
            //   App::instance('Category', $category);
            //   respond_to('Category', 'getProducts');//-> true
            return respond_to($instance, $methodName);
        }

        return false;
    }
}

if (! function_exists('compact_property')) {
    function compact_property($instance, $properties)
    {
        $properties = array_slice(func_get_args(), 1);
        $compactArray = [];
        foreach ($properties as $property) {
            if ( property_exists($instance, $property) ) {
                $reflection = new \ReflectionProperty($instance, $property);
                $reflection->setAccessible(true);
                $$property = $reflection->getValue($instance);

                $compactArray = array_merge($compactArray, compact($property));
            }
        }
        return $compactArray;
    }
}

if (! function_exists('ac_trans')) {
    function ac_trans($id, $parameters = [], $domain = 'messages', $locale = null)
    {
        $namespace = null;
        // TODO: DRY conditions
        if (! Lang::has($id)) {
            $namespace = 'authority-controller::';
            $id = $namespace.$id;

            if (! Lang::has($id)) {
                $defaultId = 'messages.unauthorized.default';
                $id = $namespace.$defaultId;

                if (! Lang::has($id)) {
                    $id = $defaultId;
                    if (Lang::has($id, 'en')) {
                        return trans($id, $parameters, $domain, 'en');
                    } else {
                        return trans($namespace.$id, $parameters, $domain, 'en');
                    }
                }
            }
        }

        return trans($id, $parameters, $domain, $locale);
    }
}


if (! function_exists('ac_trans_choice')) {
    function ac_trans_choice($id, $number, array $parameters = [], $domain = 'messages', $locale = null)
    {
        $namespace = null;
        // TODO: DRY conditions
        if (! Lang::has($id)) {
            $namespace = 'authority-controller::';
            $id = $namespace.$id;

            if (! Lang::has($id)) {
                $defaultId = 'messages.unauthorized.default';
                $id = $namespace.$defaultId;

                if (! Lang::has($id)) {
                    $id = $defaultId;
                    if (Lang::has($id, 'en')) {
                        return trans_choice($id, $number, $parameters, $domain, 'en');

                    } else {
                        return trans_choice($namespace.$id, $number, $parameters, $domain, 'en');
                    }
                }
            }
        }

        return trans_choice($id, $number, $parameters, $domain, $locale);
    }
}
