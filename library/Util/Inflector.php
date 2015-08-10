<?php

class Util_Inflector
{

    /**
     * Singular
     *
     * Takes a plural word and makes it singular
     *
     * @param string $str            
     * @return string
     */
    public static function singular ($str)
    {
        $result = strval($str);
        
        if (! self::is_countable($result)) {
            return $result;
        }
        
        $singular_rules = array(
            '/(matr)ices$/' => '\1ix',
            '/(vert|ind)ices$/' => '\1ex',
            '/^(ox)en/' => '\1',
            '/(alias)es$/' => '\1',
            '/([octop|vir])i$/' => '\1us',
            '/(cris|ax|test)es$/' => '\1is',
            '/(shoe)s$/' => '\1',
            '/(o)es$/' => '\1',
            '/(bus|campus)es$/' => '\1',
            '/([m|l])ice$/' => '\1ouse',
            '/(x|ch|ss|sh)es$/' => '\1',
            '/(m)ovies$/' => '\1\2ovie',
            '/(s)eries$/' => '\1\2eries',
            '/([^aeiouy]|qu)ies$/' => '\1y',
            '/([lr])ves$/' => '\1f',
            '/(tive)s$/' => '\1',
            '/(hive)s$/' => '\1',
            '/([^f])ves$/' => '\1fe',
            '/(^analy)ses$/' => '\1sis',
            '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/' => '\1\2sis',
            '/([ti])a$/' => '\1um',
            '/(p)eople$/' => '\1\2erson',
            '/(m)en$/' => '\1an',
            '/(s)tatuses$/' => '\1\2tatus',
            '/(c)hildren$/' => '\1\2hild',
            '/(n)ews$/' => '\1\2ews',
            '/([^us])s$/' => '\1'
        );
        
        foreach ($singular_rules as $rule => $replacement) {
            if (preg_match($rule, $result)) {
                $result = preg_replace($rule, $replacement, $result);
                break;
            }
        }
        
        return $result;
    }

    /**
     * Plural
     *
     * Takes a singular word and makes it plural
     *
     * @param string $str            
     * @return string
     */
    public static function plural ($str)
    {
        $result = strval($str);
        
        if (! self::is_countable($result)) {
            return $result;
        }
        
        $plural_rules = array(
            '/^(ox)$/' => '\1\2en', // ox
            '/([m|l])ouse$/' => '\1ice', // mouse, louse
            '/(matr|vert|ind)ix|ex$/' => '\1ices', // matrix, vertex, index
            '/(x|ch|ss|sh)$/' => '\1es', // search, switch, fix, box, process, address
            '/([^aeiouy]|qu)y$/' => '\1ies', // query, ability, agency
            '/(hive)$/' => '\1s', // archive, hive
            '/(?:([^f])fe|([lr])f)$/' => '\1\2ves', // half, safe, wife
            '/sis$/' => 'ses', // basis, diagnosis
            '/([ti])um$/' => '\1a', // datum, medium
            '/(p)erson$/' => '\1eople', // person, salesperson
            '/(m)an$/' => '\1en', // man, woman, spokesman
            '/(c)hild$/' => '\1hildren', // child
            '/(buffal|tomat)o$/' => '\1\2oes', // buffalo, tomato
            '/(bu|campu)s$/' => '\1\2ses', // bus, campus
            '/(alias|status|virus)$/' => '\1es', // alias
            '/(octop)us$/' => '\1i', // octopus
            '/(ax|cris|test)is$/' => '\1es', // axis, crisis
            '/s$/' => 's', // no change (compatibility)
            '/$/' => 's'
        );
        
        foreach ($plural_rules as $rule => $replacement) {
            if (preg_match($rule, $result)) {
                $result = preg_replace($rule, $replacement, $result);
                break;
            }
        }
        
        return $result;
    }

    /**
     * Camelize
     *
     * Takes multiple words separated by spaces or underscores and camelizes them
     *
     * @param string $str            
     * @return string
     */
    public static function camelize ($str)
    {
        return strtolower($str[0]) . substr(str_replace(' ', '', ucwords(preg_replace('/[\s_]+/', ' ', $str))), 1);
    }

    /**
     * Underscore
     *
     * Takes multiple words separated by spaces and underscores them
     *
     * @param string $str            
     * @return string
     */
    public static function underscore ($str)
    {
        return preg_replace('/[\s]+/', '_', trim(MB_ENABLED ? mb_strtolower($str) : strtolower($str)));
    }

    /**
     * Humanize
     *
     * Takes multiple words separated by the separator and changes them to spaces
     *
     * @param string $str            
     * @param string $separator            
     * @return string
     */
    public static function humanize ($str, $separator = '_')
    {
        return ucwords(preg_replace('/[' . $separator . ']+/', ' ', trim(MB_ENABLED ? mb_strtolower($str) : strtolower($str))));
    }

    /**
     * Checks if the given word has a plural version.
     *
     * @param string $word
     *            check
     * @return bool
     */
    public static function is_countable ($word)
    {
        return ! in_array(strtolower($word), array(
            'equipment',
            'information',
            'rice',
            'money',
            'species',
            'series',
            'fish',
            'meta'
        ));
    }
}
