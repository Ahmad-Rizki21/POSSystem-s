<template>
  <div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Point of Sale</h2>
        <p class="mt-1 text-sm text-gray-600">Process transactions quickly and efficiently</p>
      </div>
      
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Product Search and List -->
        <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="p-4 border-b border-gray-200">
            <div class="relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
              <input
                v-model="search"
                type="text"
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Search products by name..."
              >
            </div>
          </div>
          
          <div class="overflow-y-auto max-h-[calc(100vh-250px)] md:max-h-[500px]">
            <div v-if="filteredProducts.length">
              <div v-for="product in filteredProducts" :key="product.id" class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150 last:border-b-0">
                <div class="flex items-center justify-between">
                  <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-medium text-gray-900 truncate">{{ product.name }}</h4>
                    <p class="text-sm text-gray-500 truncate">SKU: {{ product.sku }}</p>
                    <div class="flex items-center mt-1 flex-wrap gap-1">
                      <span class="text-sm font-medium text-indigo-600">{{ formatCurrency(product.selling_price) }}</span>
                      <span class="text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded-full">Stock: {{ product.stock }}</span>
                    </div>
                  </div>
                  <button 
                    @click="addToCart(product)"
                    :disabled="product.stock <= 0"
                    class="ml-4 flex-shrink-0 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                  >
                    Add to Cart
                  </button>
                </div>
              </div>
            </div>
            <div v-else class="p-8 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
              <p class="mt-1 text-sm text-gray-500">Try adjusting your search or add new products.</p>
            </div>
          </div>
        </div>
        
        <!-- Cart -->
        <div class="w-full md:w-96 bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col">
          <div class="px-4 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Cart</h3>
          </div>
          
          <div class="flex-1 overflow-y-auto p-4">
            <div v-if="cart.length">
              <div v-for="item in cart" :key="item.id" class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">{{ item.name }}</p>
                  <p class="text-sm text-gray-500 truncate">Qty: {{ item.quantity }} × {{ formatCurrency(item.price) }}</p>
                </div>
                <div class="flex items-center space-x-1 ml-2">
                  <button 
                    @click="decreaseQuantity(item)" 
                    class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  >
                    -
                  </button>
                  <span class="text-sm font-medium w-6 text-center">{{ item.quantity }}</span>
                  <button 
                    @click="increaseQuantity(item)" 
                    class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                  >
                    +
                  </button>
                  <div class="ml-2 text-sm font-medium text-gray-900 min-w-[60px] text-right">{{ formatCurrency(item.subtotal) }}</div>
                  <button 
                    @click="removeFromCart(item)" 
                    class="ml-2 text-red-600 hover:text-red-900 flex-shrink-0"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
              <p class="mt-1 text-sm text-gray-500">Add some products to your cart.</p>
            </div>
          </div>
          
          <div class="border-t border-gray-200 p-4 bg-gray-50">
            <div class="flex justify-between text-sm font-medium text-gray-900 mb-4">
              <p>Total:</p>
              <p>{{ formatCurrency(total) }}</p>
            </div>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select 
                  v-model="paymentMethod" 
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                  <option value="cash">Cash</option>
                  <option value="qris">QRIS</option>
                  <option value="transfer">Transfer</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Paid Amount</label>
                <input 
                  v-model="paidAmount" 
                  type="number" 
                  step="0.01"
                  min="0"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  placeholder="Enter amount paid"
                >
              </div>
              
              <div v-if="changeAmount >= 0" class="text-sm">
                <p>Change: <span class="font-medium text-green-600">{{ formatCurrency(changeAmount) }}</span></p>
              </div>
              
              <button 
                @click="completeTransaction"
                :disabled="cart.length === 0 || paidAmount < total"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Complete Transaction
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [],
      cart: [],
      search: '',
      paymentMethod: 'cash',
      paidAmount: 0
    }
  },
  computed: {
    filteredProducts() {
      return this.products.filter(product => 
        product.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    total() {
      return this.cart.reduce((sum, item) => sum + parseFloat(item.subtotal), 0);
    },
    changeAmount() {
      return this.paidAmount - this.total;
    }
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    fetchProducts() {
      axios.get('/api/products')
        .then(response => {
          this.products = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    addToCart(product) {
      if (product.stock <= 0) {
        alert('This product is out of stock!');
        return;
      }
      
      const existingItem = this.cart.find(item => item.id === product.id);
      
      if (existingItem) {
        if (existingItem.quantity >= product.stock) {
          alert('Not enough stock available!');
          return;
        }
        existingItem.quantity += 1;
        existingItem.subtotal = (existingItem.quantity * product.selling_price).toFixed(2);
      } else {
        if (1 > product.stock) {
          alert('Not enough stock available!');
          return;
        }
        this.cart.push({
          id: product.id,
          name: product.name,
          price: parseFloat(product.selling_price),
          quantity: 1,
          subtotal: product.selling_price
        });
      }
    },
    removeFromCart(item) {
      this.cart = this.cart.filter(cartItem => cartItem.id !== item.id);
    },
    increaseQuantity(item) {
      const product = this.products.find(p => p.id === item.id);
      if (item.quantity >= product.stock) {
        alert('Not enough stock available!');
        return;
      }
      item.quantity += 1;
      item.subtotal = (item.quantity * item.price).toFixed(2);
    },
    decreaseQuantity(item) {
      if (item.quantity > 1) {
        item.quantity -= 1;
        item.subtotal = (item.quantity * item.price).toFixed(2);
      } else {
        this.removeFromCart(item);
      }
    },
    formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount);
    },
    completeTransaction() {
      if (this.cart.length === 0) {
        alert('Cart is empty');
        return;
      }
      
      if (this.paidAmount < this.total) {
        alert('Insufficient payment');
        return;
      }
      
      if (this.paidAmount === 0) {
        alert('Please enter the amount paid');
        return;
      }
      
      axios.post('/api/transactions', {
        items: this.cart,
        payment_method: this.paymentMethod,
        paid_amount: parseFloat(this.paidAmount)
      })
      .then(response => {
        alert('Transaction completed successfully!');
        this.cart = [];
        this.paidAmount = 0;
        this.fetchProducts();
      })
      .catch(error => {
        console.error(error);
        alert('Transaction failed');
      });
    }
  }
}
</script>