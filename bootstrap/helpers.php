<?php


if (!function_exists('di')) {
    /**
     * This calls our default dependency injection.
     *
     * @param string|mixed $alias The service provider alias
     * @return Phalcon\Di|mixed
     */
    function di($alias = null)
    {
        $default = \Phalcon\Di::getDefault();

        if (is_string($alias)) {
            return $default->get($alias);
        }

        # if the alias is array then we must check the array
        # passed in
        if (is_array($alias)) {
            if (
                !isset($alias[0]) ||
                !isset($alias[1])
            ) {
                throw new InvalidArgumentException('Provider alias or callback not found');
            }

            $default->set(
                $alias[0],
                $alias[1],
                $alias[2] ?? false
            );

            return $default->get($alias[0]);
        }

        # or just return the default thing
        return $default;
    }
}

if (!function_exists('config')) {

    /**
     * This returns the service provider 'config'.
     *
     * @param string|mixed $option If string passed, it will automatically
     *      interpret as ->get(...), if array it will merge/replace based on
     *      [$merge_or_default_value = true]
     * @param bool $merge_or_default_value If true, it will automatically merge, else if false
     *      it will replace a config
     * @return mixed|Phalcon\Config
     */
    function config($option = null, $merge_or_default_value = true)
    {
        $config = di()->get('config');

        # here, if the $option is null
        # we should directly pass the di 'config'
        if ($option === null) {
            return $config;
        }

        # here, if the option is array
        # it should interpreted as updating the di 'config'
        # current structure
        if (is_array($option)) {
            if ($merge_or_default_value === true) {
                $config->merge(new Phalcon\Config($option));
                $new_config = $config->toArray();
            } else {
                $new_config = array_replace_recursive(
                    $config ? $config->toArray() : [],
                    $option
                );
            }

            # we need to re-initialize the config
            di()->set('config', function () use ($new_config) {
                return new Phalcon\Config($new_config);
            });

            return true;
        }

        return call_user_func_array(
            [$config, 'path'],
            func_get_args()
        );
    }
}
