# notificationGoogleCalendarToSlack

googleカレンダーからその日の予定を取得して通知するソフトです．

## 環境構築

googleのサービスアカウントを作成し，取得したいカレンダーに追加します．
その後，jsonでカギを作り，source/keysディレクトリを作成し，そこにkey.jsonとして保存します．
また，カレンダーのIDを取得します．

その後，incoming webhooksのtokenを取得する．
### 参考

[Google Calendar API と PHP で 予定の取得と追加をしてみるよ（準備編）](https://liginc.co.jp/472637)
[SlackのWebhook URL取得手順](https://qiita.com/vmmhypervisor/items/18c99624a84df8b31008)

```
$ cp source/.env_example source/keys/.env
```
その後，.envに必要な情報を記入する．

- calendar\_id:googleカレンダーのid
- slack\_incoming\_webhooks:incoming webhooksのtoken

```
$ cd docker-compose up -d
$ cd source/
$ docker run --rm -it -v $PWD:/app composer install
$ docker exec googlecalender_php_1 php notificationGoogleCalendarToSlack.php

```


## 実行

```
$ docker-compose ps
$ docker exec "コンテナ名" php notificationGoogleCalendarToSlack.php
```

docker-compose ps でコンテナ名を確認し，docker execの部分に入力する．
## 自動実行
crontabに何も設定していない場合，例えば毎朝7時に自動実行する場合

```
$ crontab crontab.txt

```

でよい．他の時間にしたいなどの要望があれば，自分でcrontabの勉強をすることを推奨する．
