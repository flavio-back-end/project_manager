<template>
  <div>
   
    <button @click="openAddProductForm" class="btn btn-primary">
      Add Product
    </button>


    <div v-if="showAddProductForm" class="mt-3">
      <h2>Add New Product</h2>
      <form @submit.prevent="submitNewProduct">
        <div class="form-group">
          <label for="productName">Product Name:</label>
          <input
            type="text"
            v-model="newProduct.name"
            class="form-control"
            required
          />
        </div>
        <div class="form-group">
          <label for="productDescription">Product Description:</label>
          <textarea
            v-model="newProduct.description"
            class="form-control"
            required
          ></textarea>
        </div>
        <div class="form-group">
          <label for="productSize">Product Size:</label>
          <input
            type="text"
            v-model="newProduct.size"
            class="form-control"
            required
          />
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      showAddProductForm: false,
      newProduct: {
        name: "",
        description: "",
        size: "",
      },
     
    };
  },
 

  methods: {

    openAddProductForm() {
      this.showAddProductForm = true;
    },

    closeAddProductForm() {
      this.showAddProductForm = false;
     
      this.newProduct = {
        name: "",
        description: "",
        size: "",
      };
    },

    submitNewProduct() {
      const accessToken = localStorage.getItem("access_token");

      axios
        .post("http://127.0.0.1:8000/api/products", this.newProduct, {
          headers: {
            Authorization: `Bearer ${accessToken}`,
          },
        })
        .then((response) => {
          console.log("New product added successfully:", response.data);
          this.$router.push("/ProductTable");
        })
        .catch((error) => {
          console.error("Error adding new product:", error);
        });
    },
  },
};
</script>
