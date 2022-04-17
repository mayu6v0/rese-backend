<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
</head>
<style>
  img {
    width: 100px;
  }

  .name {
    /* font-size: 18px; */
    margin: 20px 0;
  }

  .reservation {
    margin-top: 50px;
  }

  table {
    border-collapse: collapse;
  }

  table th,
  table td,
  table tr {
    border: 1px solid black;
    padding: 10px 20px;
    font-size: 17px;
  }

  table th {
    text-align: left;
  }

  .qrcode {
    margin-top: 30px;
  }
</style>

<body>
  <div class="container">
    <img src="https://m-rese.s3.ap-northeast-1.amazonaws.com/image/Rese.jpg">
    <h2 class="name">{{$name}}さん</h2>
    <p>この度は『Rese』をご利用いただき誠にありがとうございます。</p>
    <p>ご予約が完了致しました。</p>
    <p>当日のご来店をお待ちしております。</p>

    <div class="reservation">
      <h2>ご予約内容</h2>
      <table>
        <tr>
          <th>ご予約日時</th>
          <td>{{$datetime}}</td>
        </tr>
        <tr>
          <th>店名</th>
          <td>{{$restaurant}}</td>
        </tr>
        <tr>
          <th>ご予約人数</th>
          <td>{{$number}}名</td>
        </tr>
      </table>
      <p>ご予約の変更・キャンセルは<a href="https://rese-nuxt.herokuapp.com/mypage">マイページ</a>からお願いします。</p>
    </div>
    <div class="qrcode">
      <p>ご来店当日、店舗にてこちらのQRコードをご提示いただくとご案内がスムーズです。
      </p>
      {!! QrCode::encoding('UTF-8')->generate('照合する内容'); !!}
      <p hidden>QRコード</p>
    </div>
  </div>
</body>

</html>