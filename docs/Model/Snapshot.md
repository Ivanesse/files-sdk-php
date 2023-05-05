# Snapshot

## Example Snapshot Object

```
{
  "expires_at": "2000-01-01T01:00:00Z",
  "finalized_at": "2000-01-01T01:00:00Z",
  "name": "My Snapshot",
  "user_id": 1,
  "bundle_id": 1
}
```

* `expires_at` (date-time): When the snapshot expires.
* `finalized_at` (date-time): When the snapshot was finalized.
* `name` (string): A name for the snapshot.
* `user_id` (int64): The user that created this snapshot, if applicable.
* `bundle_id` (int64): The bundle using this snapshot, if applicable.
* `id` (int64): Snapshot ID.

---

## List Snapshots

```
$snapshot = new \Files\Model\Snapshot();
$snapshot->list(, [
  'per_page' => 1,
]);
```


### Parameters

* `cursor` (string): Used for pagination.  When a list request has more records available, cursors are provided in the response headers `X-Files-Cursor-Next` and `X-Files-Cursor-Prev`.  Send one of those cursor value here to resume an existing list from the next available record.  Note: many of our SDKs have iterator methods that will automatically handle cursor-based pagination.
* `per_page` (int64): Number of records to show per page.  (Max: 10,000, 1,000 or less is recommended).

---

## Show Snapshot

```
$snapshot = new \Files\Model\Snapshot();
$snapshot->find($id);
```


### Parameters

* `id` (int64): Required - Snapshot ID.

---

## Create Snapshot

```
$snapshot = new \Files\Model\Snapshot();
$snapshot->create
```


---

## Update Snapshot

```
$snapshot = current(\Files\Model\Snapshot::all());

$snapshot->update();
```

### Parameters

* `id` (int64): Required - Snapshot ID.

### Example Response

```json
{
  "expires_at": "2000-01-01T01:00:00Z",
  "finalized_at": "2000-01-01T01:00:00Z",
  "name": "My Snapshot",
  "user_id": 1,
  "bundle_id": 1
}
```

---

## Delete Snapshot

```
$snapshot = current(\Files\Model\Snapshot::all());

$snapshot->delete();
```

### Parameters

* `id` (int64): Required - Snapshot ID.
