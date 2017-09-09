This is a simple [Laravel](https://laravel.com/) based project to demonstrate Google server-side authentication and calling Youtube APIs to pull real-time live stream chat data.

 Reference links:

- [Google Sign-In for server-side apps](https://developers.google.com/identity/sign-in/web/server-side-flow)
- [YouTube Live Streaming API Overview](https://developers.google.com/youtube/v3/live/getting-started)
- [Videos](https://developers.google.com/youtube/v3/docs/videos)
- [LiveChatMessages](https://developers.google.com/youtube/v3/live/docs/liveChatMessages)
- [Google API PHP Client Library](https://developers.google.com/api-client-library/php/)

#### Setup

- You need to set up your Google credentials via the Google API Console. There are more instructions in the server-side authentication guide.
- The project assumes the host is running an https connection (Also required for sever-side authentication).
- This project uses the `.env` and assumes the following variables are present:
    * `GOOGLE_CLIENT` = your Google client id
    * `ENV_HOST` = your host name (e.g. https://something.com)
    * `GOOGLE_CREDS` = the location of your client_secret_google.json file
    * `GOOGLE_DEVELOPER_KEY` = your developer key
- Be sure to run the migration scripts to create the tables. One such way is `artisan migrate:refresh`