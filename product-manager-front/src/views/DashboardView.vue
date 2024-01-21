
<template>
  <div>
    <h2>Dashboard</h2>
    <div v-if="user">
      <p>Welcome, {{ user.name }}</p>
      <p>Email: {{ user.email }}</p>
     
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      user: null,
    };
  },
  created() {
    this.fetchUserData();
  },
  methods: {
    fetchUserData() {
      const accessToken = localStorage.getItem("access_token");

      if (!accessToken) {
        this.$router.push("/login");
        return;
      }

      axios
        .get("http://127.0.0.1:8000/api/dashboard", {
          headers: {
            Authorization: `Bearer ${accessToken}`,
          },
        })
        .then((response) => {
          this.user = response.data.user;
        })
        .catch((error) => {
          console.error("Error fetching user data:", error);
          if (error.response && error.response.status === 401) {
            this.$router.push("/login");
          }
        });
    },
  },
};
</script>
