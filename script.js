const server_link = "services/";

const products = "api/products/";
const users = "api/users/";

const ep_read = "handler.php";
const ep_create = "create.php";

let response;

function get_all_products() {
  const link = server_link + products + ep_read;
  const request = "?all";

  fetch(link + request)
    .then((response) => response.json())
    .then((json) => (response = json))
    .catch((err) => console.log("Request failed", err));
}
