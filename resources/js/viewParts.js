console.log(document.querySelector('.js-pulldown'))
console.log(document.querySelector('.js-confirm'))

// プルダウンの選択肢をクリックすると即座にリクエストを送信する
const form = document.querySelector('.js-pulldown')
document.querySelector('.js-pulldown').addEventListener('change', () => {
  console.log('onchange')
  form.submit();
})

// alertで処理を実行してよいかをユーザーに確認させる処理
document.querySelector('.js-confirm').addEventListener("click", (e) => {
  if (!window.confirm('完全削除しますか？')) {
    e.preventDefault();
  };
})
