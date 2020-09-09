<?php

declare(strict_types=1);

namespace Files\Model;

use Files\Api;
use Files\Logger;

require_once __DIR__ . '/../Files.php';

/**
 * Class Behavior
 *
 * @package Files
 */
class Behavior {
  private $attributes = [];
  private $options = [];

  function __construct($attributes = [], $options = []) {
    foreach ($attributes as $key => $value) {
      $this->attributes[str_replace('?', '', $key)] = $value;
    }

    $this->options = $options;
  }

  public function __get($name) {
    return @$this->attributes[$name];
  }

  public function isLoaded() {
    return !!@$this->attributes['id'];
  }

  // int64 # Folder behavior ID
  public function getId() {
    return @$this->attributes['id'];
  }

  public function setId($value) {
    return $this->attributes['id'] = $value;
  }

  // string # Folder path This must be slash-delimited, but it must neither start nor end with a slash. Maximum of 5000 characters.
  public function getPath() {
    return @$this->attributes['path'];
  }

  public function setPath($value) {
    return $this->attributes['path'] = $value;
  }

  // string # URL for attached file
  public function getAttachmentUrl() {
    return @$this->attributes['attachment_url'];
  }

  public function setAttachmentUrl($value) {
    return $this->attributes['attachment_url'] = $value;
  }

  // string # Behavior type.
  public function getBehavior() {
    return @$this->attributes['behavior'];
  }

  public function setBehavior($value) {
    return $this->attributes['behavior'] = $value;
  }

  // object # Settings for this behavior.  See the section above for an example value to provide here.  Formatting is different for each Behavior type.  May be sent as nested JSON or a single JSON-encoded string.  If using XML encoding for the API call, this data must be sent as a JSON-encoded string.
  public function getValue() {
    return @$this->attributes['value'];
  }

  public function setValue($value) {
    return $this->attributes['value'] = $value;
  }

  // file # Certain behaviors may require a file, for instance, the "watermark" behavior requires a watermark image
  public function getAttachmentFile() {
    return @$this->attributes['attachment_file'];
  }

  public function setAttachmentFile($value) {
    return $this->attributes['attachment_file'] = $value;
  }

  // Parameters:
  //   value - string - The value of the folder behavior.  Can be a integer, array, or hash depending on the type of folder behavior.
  //   attachment_file - file - Certain behaviors may require a file, for instance, the "watermark" behavior requires a watermark image
  //   behavior - string - Behavior type.
  //   path - string - Folder behaviors path.
  public function update($params = []) {
    if (!$this->id) {
      throw new \Error('Current object has no id');
    }

    if (!is_array($params)) {
      throw new \InvalidArgumentException('Bad parameter: $params must be of type array; received ' . gettype($params));
    }

    $params['id'] = $this->id;

    if (@$params['id'] && !is_int(@$params['id'])) {
      throw new \InvalidArgumentException('Bad parameter: $id must be of type int; received ' . gettype($id));
    }
    if (@$params['value'] && !is_string(@$params['value'])) {
      throw new \InvalidArgumentException('Bad parameter: $value must be of type string; received ' . gettype($value));
    }
    if (@$params['behavior'] && !is_string(@$params['behavior'])) {
      throw new \InvalidArgumentException('Bad parameter: $behavior must be of type string; received ' . gettype($behavior));
    }
    if (@$params['path'] && !is_string(@$params['path'])) {
      throw new \InvalidArgumentException('Bad parameter: $path must be of type string; received ' . gettype($path));
    }

    if (!@$params['id']) {
      if ($this->id) {
        $params['id'] = @$this->id;
      } else {
        throw new \Error('Parameter missing: id');
      }
    }

    return Api::sendRequest('/behaviors/' . @$params['id'] . '', 'PATCH', $params, $this->options);
  }

  public function delete($params = []) {
    if (!$this->id) {
      throw new \Error('Current object has no id');
    }

    if (!is_array($params)) {
      throw new \InvalidArgumentException('Bad parameter: $params must be of type array; received ' . gettype($params));
    }

    $params['id'] = $this->id;

    if (@$params['id'] && !is_int(@$params['id'])) {
      throw new \InvalidArgumentException('Bad parameter: $id must be of type int; received ' . gettype($id));
    }

    if (!@$params['id']) {
      if ($this->id) {
        $params['id'] = @$this->id;
      } else {
        throw new \Error('Parameter missing: id');
      }
    }

    return Api::sendRequest('/behaviors/' . @$params['id'] . '', 'DELETE', $params, $this->options);
  }

  public function destroy($params = []) {
    return $this->delete($params);
  }

  public function save() {
      if (@$this->attributes['id']) {
        return $this->update($this->attributes);
      } else {
        $new_obj = self::create($this->attributes, $this->options);
        $this->attributes = $new_obj->attributes;
        return true;
      }
  }

  // Parameters:
  //   page - int64 - Current page number.
  //   per_page - int64 - Number of records to show per page.  (Max: 10,000, 1,000 or less is recommended).
  //   action - string - Deprecated: If set to `count` returns a count of matching records rather than the records themselves.
  //   cursor - string - Send cursor to resume an existing list from the point at which you left off.  Get a cursor from an existing list via the X-Files-Cursor-Next header.
  //   sort_by - object - If set, sort records by the specified field in either 'asc' or 'desc' direction (e.g. sort_by[last_login_at]=desc). Valid fields are `site_id` and `behavior`.
  //   filter - object - If set, return records where the specifiied field is equal to the supplied value. Valid fields are `behavior`.
  //   filter_gt - object - If set, return records where the specifiied field is greater than the supplied value. Valid fields are `behavior`.
  //   filter_gteq - object - If set, return records where the specifiied field is greater than or equal to the supplied value. Valid fields are `behavior`.
  //   filter_like - object - If set, return records where the specifiied field is equal to the supplied value. Valid fields are `behavior`.
  //   filter_lt - object - If set, return records where the specifiied field is less than the supplied value. Valid fields are `behavior`.
  //   filter_lteq - object - If set, return records where the specifiied field is less than or equal to the supplied value. Valid fields are `behavior`.
  //   behavior - string - If set, only shows folder behaviors matching this behavior type.
  public static function list($params = [], $options = []) {
    if (@$params['page'] && !is_int(@$params['page'])) {
      throw new \InvalidArgumentException('Bad parameter: $page must be of type int; received ' . gettype($page));
    }

    if (@$params['per_page'] && !is_int(@$params['per_page'])) {
      throw new \InvalidArgumentException('Bad parameter: $per_page must be of type int; received ' . gettype($per_page));
    }

    if (@$params['action'] && !is_string(@$params['action'])) {
      throw new \InvalidArgumentException('Bad parameter: $action must be of type string; received ' . gettype($action));
    }

    if (@$params['cursor'] && !is_string(@$params['cursor'])) {
      throw new \InvalidArgumentException('Bad parameter: $cursor must be of type string; received ' . gettype($cursor));
    }

    if (@$params['behavior'] && !is_string(@$params['behavior'])) {
      throw new \InvalidArgumentException('Bad parameter: $behavior must be of type string; received ' . gettype($behavior));
    }

    $response = Api::sendRequest('/behaviors', 'GET', $params, $options);

    $return_array = [];

    foreach ($response->data as $obj) {
      $return_array[] = new Behavior((array)$obj, $options);
    }

    return $return_array;
  }

  public static function all($params = [], $options = []) {
    return self::list($params, $options);
  }

  // Parameters:
  //   id (required) - int64 - Behavior ID.
  public static function find($id, $params = [], $options = []) {
    if (!is_array($params)) {
      throw new \InvalidArgumentException('Bad parameter: $params must be of type array; received ' . gettype($params));
    }

    $params['id'] = $id;

    if (!@$params['id']) {
      throw new \Error('Parameter missing: id');
    }

    if (@$params['id'] && !is_int(@$params['id'])) {
      throw new \InvalidArgumentException('Bad parameter: $id must be of type int; received ' . gettype($id));
    }

    $response = Api::sendRequest('/behaviors/' . @$params['id'] . '', 'GET', $params, $options);

    return new Behavior((array)(@$response->data ?: []), $options);
  }

  public static function get($id, $params = [], $options = []) {
    return self::find($id, $params, $options);
  }

  // Parameters:
  //   page - int64 - Current page number.
  //   per_page - int64 - Number of records to show per page.  (Max: 10,000, 1,000 or less is recommended).
  //   action - string - Deprecated: If set to `count` returns a count of matching records rather than the records themselves.
  //   cursor - string - Send cursor to resume an existing list from the point at which you left off.  Get a cursor from an existing list via the X-Files-Cursor-Next header.
  //   sort_by - object - If set, sort records by the specified field in either 'asc' or 'desc' direction (e.g. sort_by[last_login_at]=desc). Valid fields are `site_id` and `behavior`.
  //   filter - object - If set, return records where the specifiied field is equal to the supplied value. Valid fields are `behavior`.
  //   filter_gt - object - If set, return records where the specifiied field is greater than the supplied value. Valid fields are `behavior`.
  //   filter_gteq - object - If set, return records where the specifiied field is greater than or equal to the supplied value. Valid fields are `behavior`.
  //   filter_like - object - If set, return records where the specifiied field is equal to the supplied value. Valid fields are `behavior`.
  //   filter_lt - object - If set, return records where the specifiied field is less than the supplied value. Valid fields are `behavior`.
  //   filter_lteq - object - If set, return records where the specifiied field is less than or equal to the supplied value. Valid fields are `behavior`.
  //   path (required) - string - Path to operate on.
  //   recursive - string - Show behaviors above this path?
  //   behavior - string - DEPRECATED: If set only shows folder behaviors matching this behavior type. Use `filter[behavior]` instead.
  public static function listFor($path, $params = [], $options = []) {
    if (!is_array($params)) {
      throw new \InvalidArgumentException('Bad parameter: $params must be of type array; received ' . gettype($params));
    }

    $params['path'] = $path;

    if (!@$params['path']) {
      throw new \Error('Parameter missing: path');
    }

    if (@$params['page'] && !is_int(@$params['page'])) {
      throw new \InvalidArgumentException('Bad parameter: $page must be of type int; received ' . gettype($page));
    }

    if (@$params['per_page'] && !is_int(@$params['per_page'])) {
      throw new \InvalidArgumentException('Bad parameter: $per_page must be of type int; received ' . gettype($per_page));
    }

    if (@$params['action'] && !is_string(@$params['action'])) {
      throw new \InvalidArgumentException('Bad parameter: $action must be of type string; received ' . gettype($action));
    }

    if (@$params['cursor'] && !is_string(@$params['cursor'])) {
      throw new \InvalidArgumentException('Bad parameter: $cursor must be of type string; received ' . gettype($cursor));
    }

    if (@$params['path'] && !is_string(@$params['path'])) {
      throw new \InvalidArgumentException('Bad parameter: $path must be of type string; received ' . gettype($path));
    }

    if (@$params['recursive'] && !is_string(@$params['recursive'])) {
      throw new \InvalidArgumentException('Bad parameter: $recursive must be of type string; received ' . gettype($recursive));
    }

    if (@$params['behavior'] && !is_string(@$params['behavior'])) {
      throw new \InvalidArgumentException('Bad parameter: $behavior must be of type string; received ' . gettype($behavior));
    }

    $response = Api::sendRequest('/behaviors/folders/' . @$params['path'] . '', 'GET', $params, $options);

    $return_array = [];

    foreach ($response->data as $obj) {
      $return_array[] = new Behavior((array)$obj, $options);
    }

    return $return_array;
  }

  // Parameters:
  //   value - string - The value of the folder behavior.  Can be a integer, array, or hash depending on the type of folder behavior.
  //   attachment_file - file - Certain behaviors may require a file, for instance, the "watermark" behavior requires a watermark image
  //   path (required) - string - Folder behaviors path.
  //   behavior (required) - string - Behavior type.
  public static function create($params = [], $options = []) {
    if (!@$params['path']) {
      throw new \Error('Parameter missing: path');
    }

    if (!@$params['behavior']) {
      throw new \Error('Parameter missing: behavior');
    }

    if (@$params['value'] && !is_string(@$params['value'])) {
      throw new \InvalidArgumentException('Bad parameter: $value must be of type string; received ' . gettype($value));
    }

    if (@$params['path'] && !is_string(@$params['path'])) {
      throw new \InvalidArgumentException('Bad parameter: $path must be of type string; received ' . gettype($path));
    }

    if (@$params['behavior'] && !is_string(@$params['behavior'])) {
      throw new \InvalidArgumentException('Bad parameter: $behavior must be of type string; received ' . gettype($behavior));
    }

    $response = Api::sendRequest('/behaviors', 'POST', $params, $options);

    return new Behavior((array)(@$response->data ?: []), $options);
  }

  // Parameters:
  //   url (required) - string - URL for testing the webhook.
  //   method - string - HTTP method(GET or POST).
  //   encoding - string - HTTP encoding method.  Can be JSON, XML, or RAW (form data).
  //   headers - object - Additional request headers.
  //   body - object - Additional body parameters.
  //   action - string - action for test body
  public static function webhookTest($params = [], $options = []) {
    if (!@$params['url']) {
      throw new \Error('Parameter missing: url');
    }

    if (@$params['url'] && !is_string(@$params['url'])) {
      throw new \InvalidArgumentException('Bad parameter: $url must be of type string; received ' . gettype($url));
    }

    if (@$params['method'] && !is_string(@$params['method'])) {
      throw new \InvalidArgumentException('Bad parameter: $method must be of type string; received ' . gettype($method));
    }

    if (@$params['encoding'] && !is_string(@$params['encoding'])) {
      throw new \InvalidArgumentException('Bad parameter: $encoding must be of type string; received ' . gettype($encoding));
    }

    if (@$params['action'] && !is_string(@$params['action'])) {
      throw new \InvalidArgumentException('Bad parameter: $action must be of type string; received ' . gettype($action));
    }

    $response = Api::sendRequest('/behaviors/webhook/test', 'POST', $params, $options);

    return $response->data;
  }
}
