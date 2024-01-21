<template>
  <div>
    <h1>Edit Product</h1>
    <form @submit.prevent="saveChanges">
      <div class="form-group">
        <label for="productName">Product Name:</label>
        <input
          type="text"
          id="productName"
          v-model="editedProduct.name"
          class="form-control"
          required
        />
      </div>

      <div class="form-group">
        <label for="productDescription">Product Description:</label>
        <textarea
          id="productDescription"
          v-model="editedProduct.description"
          class="form-control"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="productSize">Product Size:</label>
        <input
          type="text"
          id="productSize"
          v-model="editedProduct.size"
          class="form-control"
        />
      </div>

      <button type="submit" class="btn btn-primary">Save Changes</button>
      <button @click="cancelEdit" class="btn btn-secondary">Cancel</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      editedProduct: {
        id: null,
        name: "",
        description: "",
        size: "",
      },
    };
  },
  mounted() {
    const productId = this.$route.params.id;
    this.fetchProductDetails(productId);
  },
  methods: {
    fetchProductDetails(productId) {
      const accessToken = localStorage.getItem("access_token");
      axios
        .get(`http://127.0.0.1:8000/api/products/${productId}`, {
          headers: {
            Authorization: `Bearer ${accessToken}`,
          },
        })
        .then((response) => {
          this.editedProduct = response.data.products;
        })
        .catch((error) => {
          console.error("Error fetching product details:", error);
        });
    },
    saveChanges() {
      const accessToken = localStorage.getItem("access_token");
      const productId = this.editedProduct.id;

      axios
        .put(
          `http://127.0.0.1:8000/api/products/${productId}`,
          this.editedProduct,
          {
            headers: {
              Authorization: `Bearer ${accessToken}`,
            },
          }
        )
        .then(() => {
          console.log(`Product with ID ${productId} updated successfully.`);
          this.$router.push("/product-list");
        })
        .catch((error) => {
          console.error("Error updating product:", error);
        });
    },
    cancelEdit() {
      this.$router.push("/product-list");
    },
  },
};
</script>
