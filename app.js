document.querySelector('#mailing-list form').addEventListener('submit', function (e) {
  e.preventDefault();
  const email = e.target.email.value;
  if (email) {
    alert('Thanks for subscribing!');
    e.target.reset();
  }
});
