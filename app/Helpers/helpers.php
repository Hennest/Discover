<?php

if (! function_exists('admin_trans')) {
    /**
     * Translate the given message.
     *
     * @param string $key
     * @param array  $replace
     * @param string $locale
     *
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function admin_trans($key, $replace = [], $locale = null)
    {
        static $method = null;

        if ($method === null) {
            $method = version_compare(app()->version(), '6.0', '>=') ? 'get' : 'trans';
        }

        $translator = app('translator');
        if ($translator->has($key, $locale)) {
            return $translator->$method($key, $replace, $locale);
        }
        if (
            mb_strpos($key, 'global.') !== 0
            && count($arr = explode('.', $key)) > 1
        ) {
            unset($arr[0]);
            array_unshift($arr, 'global');
            $key = implode('.', $arr);

            if (! $translator->has($key)) {
                return end($arr);
            }

            return $translator->$method($key, $replace, $locale);
        }

        return last(explode('.', $key));
    }
}
