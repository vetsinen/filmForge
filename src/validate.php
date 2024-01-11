<?php

function clearString(string $string):string
{
    return filter_var(mb_trim($string), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

function clearUser($data)
{
    $data['username'] = clearString($data['username']);
    $data['password'] = clearString($data['password']);
    return $data;
}
function mb_trim($string, $trim_chars = '\s'){
    return preg_replace('/^['.$trim_chars.']*(?U)(.*)['.$trim_chars.']*$/u', '\\1',$string);
}
function filmDataValidator($data)
{
    if (!(isset($data['title']) && isset($data['format']) && isset($data['release_year']) && isset($data['actors']))) return false;

    foreach ($data as $key => $value) $data[$key] = clearString($value);
    if (strlen($data['title']<2) || strlen($data['actors'])< 3) return false;

    return $data;
}