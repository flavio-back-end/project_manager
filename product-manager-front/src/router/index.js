import { createRouter, createWebHistory } from "vue-router";
import LoginView from "../views/LoginView.vue";
import axios from "axios";

const routes = [
  {
    path: "/login",
    name: "login",
    component: LoginView,
  },
  {
    path: "/register",
    name: "register",
    component: () =>
      import("../views/RegisterView.vue"),
  },

  {
    path: "/DashboardView",
    name: "DashboardView",
    component: () =>
      import( "../views/DashboardView.vue"),
    meta: {
      authRequired: true, 
    },
  },
  {
    path: "/ProductTable",
    name: "ProductTable",
    component: () =>
      import( "../views/ProductTable.vue"),
    meta: {
      authRequired: true, 
    },
  },
  {
    path: "/Add_product",
    name: "Add_product",
    component: () =>
      import( "../views/Add_product.vue"),
    meta: {
      authRequired: true, // Set to true for protected routes
    },
  },
  {
    path: "/EditProductPage",
    name: "EditProductPage",
    component: () =>
      import( "../views/EditProductPage.vue"),
    meta: {
      authRequired: true, 
    },
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const accessToken = localStorage.getItem("access_token");

  if (to.matched.some((record) => record.meta.authRequired)) {
    if (accessToken) {
      axios.defaults.headers.common["Authorization"] = `Bearer ${accessToken}`;
      next();
    } else {
      alert("You must be logged in to access this area");
      router.push("/");
    }
  } else {
    next();
  }
});

export default router;
