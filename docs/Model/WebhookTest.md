# WebhookTest

## Example WebhookTest Object

```
{
  "code": 200,
  "message": "",
  "status": "",
  "data": "",
  "success": true
}
```

* `code` (int64): Status HTTP code
* `message` (string): Error message
* `status` (string): Status message
* `data`: Additional data
* `success` (boolean): The success status of the webhook test
* `url` (string): URL for testing the webhook.
* `method` (string): HTTP method(GET or POST).
* `encoding` (string): HTTP encoding method.  Can be JSON, XML, or RAW (form data).
* `headers` (object): Additional request headers.
* `body` (object): Additional body parameters.
* `raw_body` (string): raw body text
* `file_as_body` (boolean): Send the file data as the request body?
* `file_form_field` (string): Send the file data as a named parameter in the request POST body
* `action` (string): action for test body

---

## Create Webhook Test

```
$webhook_test = new \Files\Model\WebhookTest();
$webhook_test->create(, [
  'url' => "https://www.site.com/...",
  'method' => "GET",
  'encoding' => "RAW",
  'headers' => "x-test-header => testvalue",
  'body' => "test-param => testvalue",
  'raw_body' => "test body",
  'file_as_body' => "false",
  'file_form_field' => "upload_file_data",
  'action' => "test",
]);
```


### Parameters

* `url` (string): Required - URL for testing the webhook.
* `method` (string): HTTP method(GET or POST).
* `encoding` (string): HTTP encoding method.  Can be JSON, XML, or RAW (form data).
* `headers` (object): Additional request headers.
* `body` (object): Additional body parameters.
* `raw_body` (string): raw body text
* `file_as_body` (boolean): Send the file data as the request body?
* `file_form_field` (string): Send the file data as a named parameter in the request POST body
* `action` (string): action for test body