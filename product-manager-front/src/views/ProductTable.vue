<template>
  <div>
    <h1 class="mb-4">Product Table</h1>

 

    <button @click="moveAddProductForm" class="btn btn-primary">
      Add Product
    </button>
    <div class="mb-3">
      <label class="mr-2">Filter by:</label>
      <div class="d-flex">
        <input
          type="text"
          v-model="nameFilter"
          @input="handleFilter"
          placeholder="Name"
          class="form-control form-control-sm mr-2"
        />
        <input
          type="text"
          v-model="sizeFilter"
          @input="handleFilter"
          placeholder="Size"
          class="form-control form-control-sm mr-2"
        />
        <input
          type="text"
          v-model="idFilter"
          @input="handleFilter"
          placeholder="ID"
          class="form-control form-control-sm"
        />
      </div>
    </div>
    <div class="d-flex justify-content-end mb-3">
      <label for="search" class="mr-2">Search:</label>
      <input
        type="text"
        id="search"
        v-model="searchTerm"
        @input="handleSearch"
      />
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="thead-dark">
          <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Size</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in filteredData" :key="product.id">
            <td>{{ product.id }}</td>
            <td>{{ product.name }}</td>
            <td>{{ product.description }}</td>
            <td>{{ product.size }}</td>
            <td>
              <!-- Edit Icon -->
              <button
                @click="editProduct(product.id)"
                class="btn btn-sm btn-warning"
              >
                <i class="fa fa-edit"></i> Edit
              </button>

              <button
                @click="deleteProduct(product.id)"
                class="btn btn-sm btn-danger ml-2"
              >
                <i class="fa fa-trash"></i> Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <nav aria-label="Page navigation">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button
            class="page-link"
            @click="prevPage"
            :disabled="currentPage === 1"
          >
            <span aria-hidden="true">&laquo;</span>
          </button>
        </li>
        <li class="page-item">
          <span class="page-link">{{ currentPage }}</span>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <button
            class="page-link"
            @click="nextPage"
            :disabled="currentPage === totalPages"
          >
            <span aria-hidden="true">&raquo;</span>
          </button>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      allData: [],
      filteredData: [],
      itemsPerPage: 2,
      currentPage: 1,
      searchTerm: "",
      nameFilter: "",
      sizeFilter: "",
      idFilter: "",
      ProductId: null,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(
        (this.filteredData && this.filteredData.length) || 0 / this.itemsPerPage
      );
    },
    paginatedData() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredData.slice(start, end);
    },
  },

  mounted() {
    // Fetch product data from your Laravel API
    this.fetchProductData();
  },
  methods: {
    fetchProductData() {
      const accessToken = localStorage.getItem("access_token");
      axios
        .get("http://127.0.0.1:8000/api/products", {
          headers: {
            Authorization: `Bearer ${accessToken}`,
          },
        })
        .then((response) => {
          this.allData = response.data.products;
          this.filterData(); // Call the filterData function after fetching data
        })
        .catch((error) => {
          console.error("Error fetching product data:", error);
        });
    },
    moveAddProductForm() {
      this.$router.push("/Add_product");
    },
    filterData() {
      // Filter products based on search term, name, size, and id
      this.filteredData = this.allData.filter((product) => {
        const searchTerm = this.searchTerm.toLowerCase();
        const nameFilter = this.nameFilter.toLowerCase();
        const sizeFilter = this.sizeFilter.toLowerCase();
        const idFilter = this.idFilter.toLowerCase();

        return (
          (product.name.toLowerCase().includes(searchTerm) ||
            product.description.toLowerCase().includes(searchTerm)) &&
          product.name.toLowerCase().includes(nameFilter) &&
          product.size.toLowerCase().includes(sizeFilter) &&
          product.id.toString().includes(idFilter)
        );
      });
    },
    handleSearch() {
      this.currentPage = 1; 
    },
    handleFilter() {
      this.currentPage = 1; 
      this.filterData();
    },
    editProduct(productId) {

      this.$router.push(`/EditProductPage/${productId}`);
    },
    deleteProduct(productId) {
      if (confirm("Are you sure you want to delete this product?")) {
        const accessToken = localStorage.getItem("access_token");
        axios
          .delete(`http://127.0.0.1:8000/api/products/${productId}`, {
            headers: {
              Authorization: `Bearer ${accessToken}`,
            },
          })
          .then(() => {
            console.log(`Product with ID ${productId} deleted successfully.`);
            this.fetchProductData();
          })
          .catch((error) => {
            console.error("Error deleting product:", error);
          });
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage += 1;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage -= 1;
      }
    },
  },
};
</script>

<style>
.pagination {
  margin-top: 20px;
}

.pagination button {
  margin: 0 5px;
  padding: 5px 10px;
  cursor: pointer;
}
</style>
