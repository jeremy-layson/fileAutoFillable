## About this trait

This trait is for lazy-ass devs out there. Adding it to your requests will enable you to skip the uploading process for typical files that requires simple validation.

## How to use

- Add this file to your `app/Http/Requests` folder
- Add it to your request
- Add `__fill` to your request data (e.g. if you want to automatically upload the `profile_picture` field then pass it as `profile_picture__fill`)