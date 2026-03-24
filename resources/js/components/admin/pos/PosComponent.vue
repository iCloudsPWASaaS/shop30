<template>
  <LoadingComponent :props="loading" />
  
  <div class="md:w-[calc(100%-340px)] lg:w-[calc(100%-320px)] xl:w-[calc(100%-377px)]" style="position:relative; height:100%;"> <!-- extra category swiper -->

    <!-- Level 1 Swiper -->
    <div class="swiper pos-menu-swiper mb-2" v-if="level1Categories.length > 0">
        <Swiper dir="ltr" :speed="1000" slidesPerView="auto" :spaceBetween="8" class="menu-slides">

            <SwiperSlide class="!w-fit">
                <router-link to="#" @click.prevent="allCategory"
                    class="min-w-[90px] h-10 flex items-center justify-center text-center px-3 rounded-lg border-b-2 border-transparent transition bg-[#EBF8FF] hover:bg-[#BEE3F8] hover:border-[#3182CE]"
                    :class="selectedLevel1 === null ? '!border-[#3182CE] !bg-[#BEE3F8] font-bold' : ''">
                    <h3 class="text-xs font-medium font-rubik text-[#2B6CB0]">All</h3>
                </router-link>
            </SwiperSlide>

            <SwiperSlide class="!w-fit" v-for="cat in level1Categories" :key="cat.id">
                <router-link to="#" @click.prevent="selectLevel1(cat.id)"
                    class="min-w-[90px] h-10 flex items-center justify-center text-center px-3 rounded-lg border-b-2 border-transparent transition bg-[#EBF8FF] hover:bg-[#BEE3F8] hover:border-[#3182CE]"
                    :class="selectedLevel1 === cat.id ? '!border-[#3182CE] !bg-[#BEE3F8] font-bold' : ''">
                    <h3 class="text-xs font-medium font-rubik text-[#2B6CB0]">{{ cat.name }}</h3>
                </router-link>
            </SwiperSlide>

        </Swiper>
    </div>

    <!-- Level 2 Swiper -->
    <div class="swiper pos-menu-swiper mb-2" v-if="level2Categories.length > 0">
        <Swiper dir="ltr" :speed="1000" slidesPerView="auto" :spaceBetween="8" class="menu-slides">

            <SwiperSlide class="!w-fit">
                <router-link to="#" @click.prevent="selectLevel2(null)"
                    class="min-w-[90px] h-10 flex items-center justify-center text-center px-3 rounded-lg border-b-2 border-transparent transition bg-[#F0FFF4] hover:bg-[#C6F6D5] hover:border-[#38A169]"
                    :class="selectedLevel2 === null ? '!border-[#38A169] !bg-[#C6F6D5] font-bold' : ''">
                    <h3 class="text-xs font-medium font-rubik text-[#276749]">All</h3>
                </router-link>
            </SwiperSlide>

            <SwiperSlide class="!w-fit" v-for="cat in level2Categories" :key="cat.id">
                <router-link to="#" @click.prevent="selectLevel2(cat.id)"
                    class="min-w-[90px] h-10 flex items-center justify-center text-center px-3 rounded-lg border-b-2 border-transparent transition bg-[#F0FFF4] hover:bg-[#C6F6D5] hover:border-[#38A169]"
                    :class="selectedLevel2 === cat.id ? '!border-[#38A169] !bg-[#C6F6D5] font-bold' : ''">
                    <h3 class="text-xs font-medium font-rubik text-[#276749]">{{ cat.name }}</h3>
                </router-link>
            </SwiperSlide>

        </Swiper>
    </div>

    <!-- Level 3 Swiper -->
    <div class="swiper pos-menu-swiper mb-4" v-if="level3Categories.length > 0">
        <Swiper dir="ltr" :speed="1000" slidesPerView="auto" :spaceBetween="8" class="menu-slides">

            <SwiperSlide class="!w-fit">
                <router-link to="#" @click.prevent="selectLevel3(null)"
                    class="min-w-[90px] h-10 flex items-center justify-center text-center px-3 rounded-lg border-b-2 border-transparent transition bg-[#FFF0F6] hover:bg-[#FFCCE5] hover:border-[#D6336C]"
                    :class="selectedLevel3 === null ? '!border-[#D6336C] !bg-[#FFCCE5] font-bold' : ''">
                    <h3 class="text-xs font-medium font-rubik text-[#A61E4D]">All</h3>
                </router-link>
            </SwiperSlide>

            <SwiperSlide class="!w-fit" v-for="sub in level3Categories" :key="sub.id">
                <router-link to="#" @click.prevent="selectLevel3(sub.id)"
                    class="min-w-[90px] h-10 flex items-center justify-center text-center px-3 rounded-lg border-b-2 border-transparent transition bg-[#FFF0F6] hover:bg-[#FFCCE5] hover:border-[#D6336C]"
                    :class="selectedLevel3 === sub.id ? '!border-[#D6336C] !bg-[#FFCCE5] font-bold' : ''">
                    <h3 class="text-xs font-medium font-rubik text-[#A61E4D]">{{ sub.name }}</h3>
                </router-link>
            </SwiperSlide>

        </Swiper>
    </div>

        <ProductListComponent v-if="products.length > 0" :products="products" />

        <PoscustomerComponent :show="showCustomerPanel" v-on:onCustomverCreate="onCustomverCreate" @close="showCustomerPanel = false" />
        <PaymentComponent :show="showPaymentPanel" v-on:orderSubmit="orderSubmit"
    :total="currencyFormat((subtotal + totalTax) - posDiscount, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position)" @close="showPaymentPanel = false" />

    <!-- Hold Orders Panel -->
    <div v-if="showHoldOrdersPanel" style="position:absolute;top:0;left:0;width:100%;height:100%;background:#fff;z-index:9999;display:flex;flex-direction:column;border-radius:12px;border:1px solid #D9DBE9;overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #dee2e6;background:#f8f9fa;position:sticky;top:0;">
            <h2 style="margin:0;font-size:18px;font-weight:700;">HOLD ORDERS</h2>
            <button @click="showHoldOrdersPanel = false" style="background:#6c757d;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:14px;font-weight:600;cursor:pointer;">
                ✕ Close
            </button>
        </div>
        <div style="flex:1;overflow-y:auto;padding:20px;">
            <div v-if="holdOrders.length === 0" class="text-center py-8">
                <p style="font-size:16px;color:#6c757d;font-weight:500;">No held orders</p>
            </div>
            <table v-else class="table table-hover table-bordered" style="width:100%;font-size:14px;">
              <thead>
                  <tr class="table-light">
                      <th style="padding:10px;font-size:12px;font-weight:700;text-transform:uppercase;color:#495057;">Customer</th>
                      <th style="padding:10px;font-size:12px;font-weight:700;text-transform:uppercase;color:#495057;text-align:center;">Items</th>
                      <th style="padding:10px;font-size:12px;font-weight:700;text-transform:uppercase;color:#495057;text-align:right;">Total</th>
                      <th style="padding:10px;font-size:12px;font-weight:700;text-transform:uppercase;color:#495057;text-align:center;">Time</th>
                      <th style="padding:10px;font-size:12px;font-weight:700;text-transform:uppercase;color:#495057;text-align:center;">Actions</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="order in holdOrders" :key="order.id" style="vertical-align:middle;">
                      <td style="padding:12px 10px;font-weight:600;">{{ order.customerName || $t('label.walk_in') }}</td>
                      <td style="padding:12px 10px;text-align:center;">
                          <span style="background:#e9ecef;border-radius:20px;padding:2px 12px;font-weight:700;">
                              {{ order.items.reduce((sum, item) => sum + item.quantity, 0) }}
                          </span>
                      </td>
                      <td style="padding:12px 10px;text-align:right;font-weight:700;color:#198754;">
                          {{ currencyFormat(order.subtotal - (order.discount || 0), setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }}
                      </td>
                      <td style="padding:12px 10px;text-align:center;color:#6c757d;">
                          {{ formatHoldTime(order.timestamp) }}
                      </td>
                      <td style="padding:12px 10px;text-align:center;">
                          <button @click.prevent="loadHoldOrder(order.id)"
                              style="background:#0d6efd;color:#fff;border:none;border-radius:8px;padding:6px 14px;font-size:13px;font-weight:600;cursor:pointer;">
                              LOAD
                          </button>
                          <button @click.prevent="deleteHoldOrder(order.id)"
                              style="background:#dc3545;color:#fff;border:none;border-radius:8px;padding:6px 14px;font-size:13px;font-weight:600;cursor:pointer;margin-left:6px;">
                              DELETE
                          </button>
                      </td>
                  </tr>
              </tbody>
            </table>
        </div>
    </div>
    <!-- Hold Orders Panel End -->
  </div>

  <div id="pos-cart"
    class="db-pos-cartDiv fixed top-0 ltr:right-0 rtl:left-0 w-full h-dvh rounded-none z-50 md:z-10 md:top-[85px] ltr:md:right-5 rtl:md:left-5 md:w-[322px] lg:w-[305px] xl:w-[360px] md:h-[calc(100vh-85px)] md:rounded-lg overflow-y-auto thin-scrolling bg-white">
    <div class="p-4">
      <div class="md:hidden text-right mb-3">
        <button class="db-pos-cartCls" @click="closeCanvas('pos-cart')">
          <i class="lab-line-circle-cross text-lg text-[#E93C3C]"></i>
        </button>
      </div>
      <div class="db-field mb-3">
        <BarcodeProductComponent />
      </div>
      <div class="flex gap-2 mb-3">
        <vue-select
          class="db-field-control  w-full flex-auto text-sm rounded-lg appearance-none cursor-pointer text-heading border-[#D9DBE9]"
          id="customer" v-model="checkoutProps.form.customer_id" :options="customers" label-by="name" value-by="id"
          :closeOnSelect="true" :searchable="true" :clearOnClose="true" :placeholder="$t('label.select_customer')"
          :search-placeholder="$t('label.search_customer')" />

        <button @click="addCustomer" type="button"
          class="flex items-center justify-center gap-1.5 px-3 h-10 rounded-lg text-white bg-primary">
          <i class="lab lab-add-circle-line"></i>
          <span class="capitalize text-sm font-bold">{{ $t('button.add') }}</span>
        </button>
      </div>
    </div>

    <div v-if="carts.length === 0" class="flex items-center justify-center">
      <img class="w-52" :src="setting.image_cart" alt="empty">
    </div>

    <ul v-if="carts.length > 0" class="p-4">
      <li v-for="(cart, index) in carts"
        class="flex items-start gap-3 pb-4 mb-4 border-b last:mb-0 last:pb-0 last:border-none border-gray-100">
        <!-- <img :src="cart.image" alt="products" class="w-28 rounded-lg flex-shrink-0" /> --> <!-- extra -->
        <div class="relative w-full overflow-hidden">
          <h4 class="font-semibold capitalize whitespace-nowrap overflow-hidden text-ellipsis mb-1">
            {{ cart.name }}
          </h4>
          <div v-if="cart.variation_id > 0" class="flex flex-wrap mb-2">
            <span class="text-xs capitalize inline-flex items-center">{{ cart.variation_names }}</span>
          </div>
          <div class="flex flex-wrap gap-3 mb-3">
            <span class="font-semibold font-sans">
              {{
                currencyFormat(cart.price, setting.site_digit_after_decimal_point,
                  setting.site_default_currency_symbol, setting.site_currency_position)
              }}
            </span>
            <del v-if="cart.discount > 0" class="font-semibold font-sans text-[#FF6262]">
              {{
                currencyFormat(cart.old_price, setting.site_digit_after_decimal_point,
                  setting.site_default_currency_symbol, setting.site_currency_position)
              }}
            </del>
          </div>
          <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-1 w-20 p-1 rounded-full bg-[#F7F7FC]">
              <button @click.prevent="quantityDecrement(index, cart)" type="button"
                :class="cart.quantity === 1 ? 'cursor-not-allowed' : ''"
                class="lab-fill-circle-minus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
              <input v-on:keypress="onlyNumber($event)" v-on:keyup="quantityUp(index, cart, $event)" type="number"
                v-model="cart.quantity" class="text-center w-full h-5 text-sm font-medium">
              <button :class="cart.quantity >= cart.stock ? 'cursor-not-allowed' : ''"
                @click.prevent="quantityIncrement(index, cart)" type="button"
                class="lab-fill-circle-plus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
            </div>
            <button @click.prevent="removeProduct(index)"
              class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#FFF4F4] text-[#E93C3C] transition-all duration-300 hover:bg-[#E93C3C] hover:text-white">
              <i class="lab-line-trash text-sm"></i>
              <span class="text-xs font-medium capitalize hidden sm:block">{{ $t('button.remove') }}</span>
            </button>
          </div>
        </div>
      </li>
    </ul>

    <div class="p-4">
      <div class="flex h-[38px]" v-if="carts.length > 0">
        <div class="db-field-down-arrow">
          <select v-model="discountType"
            class="w-[120px] h-full cursor-pointer text-sm font-client ltr:rounded-tl ltr:rounded-bl rtl:rounded-tr rtl:rounded-br appearance-none border ltr:pl-3 rtl:pr-3 text-heading border-[#EFF0F6]">
            <option :value="discountTypeEnum.PERCENTAGE">{{ $t("label.percentage") }}</option>
            <option :value="discountTypeEnum.FIXED">{{ $t("label.fixed") }}</option>
          </select>
        </div>
        <input v-model="discount" type="text" v-on:keypress="floatNumber($event)"
          :placeholder="$t('label.add_discount')" class="w-full h-full border-t border-b px-3 border-[#EFF0F6]">
        <button @click.prevent="applyDiscount" type="submit"
          class="flex-shrink-0 w-16 h-full text-sm font-medium font-client capitalize ltr:rounded-tr ltr:rounded-br rtl:rounded-tl rtl:rounded-bl text-white bg-[#008BBA]">
          {{ $t('button.apply') }}
        </button>
      </div>
      <div class="text-xs db-field-alert m-0 mt-1" v-if="discountErrorMessage">
        <span>{{ discountErrorMessage }}</span>
      </div>

      <ul class="flex flex-col gap-1.5 mt-4 mb-4">
        <li class="flex items-center justify-between">
          <span class="text-sm font-client capitalize leading-6 text-[#2E2F38]">
            {{ $t("label.sub_total") }}
          </span>
          <span class="text-sm font-client capitalize leading-6 text-[#2E2F38]">
            {{
              currencyFormat(subtotal, setting.site_digit_after_decimal_point,
                setting.site_default_currency_symbol, setting.site_currency_position)
            }}
          </span>
        </li>
        <li class="flex items-center justify-between">
          <span class="text-sm font-client capitalize leading-6">{{ $t('label.tax') }}</span>
          <span class="text-sm font-client capitalize leading-6">
            {{
              currencyFormat(totalTax, setting.site_digit_after_decimal_point,
                setting.site_default_currency_symbol, setting.site_currency_position)
            }}
          </span>
        </li>
        <li class="flex items-center justify-between">
          <span class="text-sm font-client capitalize leading-6">{{ $t("label.discount") }}</span>
          <span class="text-sm font-client capitalize leading-6">
            {{
              currencyFormat(posDiscount,
                setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                setting.site_currency_position)
            }}
          </span>
        </li>

        <li class="flex items-center justify-between">
          <span class="text-sm font-medium font-client capitalize leading-6 text-[#2E2F38]">
            {{ $t("label.total") }}
          </span>
          <span class="text-sm font-medium font-client capitalize leading-6 text-[#2E2F38]">
            {{
              currencyFormat((subtotal + totalTax) - posDiscount,
                setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                setting.site_currency_position)
            }}
          </span>
        </li>
      </ul>
      <div class="flex items-center justify-center gap-6" v-if="carts.length > 0">
        <button @click.prevent="resetCart"
          class="capitalize text-sm font-medium leading-6 font-client w-full text-center rounded-3xl py-2 text-white bg-[#FB4E4E]">
          {{ $t('button.cancel') }}
        </button>

        <button @click.prevent="holdOrder"
            class="capitalize text-sm font-medium leading-6 font-client w-full text-center rounded-3xl py-2 text-white bg-[#008BBA]">
            Hold
        </button> <!-- extra -->

        <button @click.prevent="orderPayment"
          class="capitalize text-sm font-medium leading-6 font-client w-full text-center rounded-3xl py-2 text-white bg-[#1AB759]">
          {{ $t('button.order') }}
        </button>
      </div>
    </div>
  </div>

  <button @click="openCanvas('pos-cart')" type="button"
    class="db-pos-cartBtn fixed md:hidden bottom-0 left-0 z-10 w-full h-14 py-4 text-center flex items-center justify-center shadow-xl-top gap-3 bg-primary text-white">
    <i class="lab-fill-bag text-xl"></i>
    <span class="text-base font-medium"> {{ totalProducts() }} {{ $t('label.products') }} - {{
      currencyFormat((subtotal + totalTax) - posDiscount,
        setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
        setting.site_currency_position)
    }}</span>
  </button>


  <ReceiptComponent :order="order" />
  
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import ProductListComponent from "./ProductListComponent";
import sourceEnum from "../../../enums/modules/sourceEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import paymentTypeEnum from "../../../enums/modules/paymentTypeEnum";
import roleEnum from "../../../enums/modules/roleEnum";
import appService from "../../../services/appService";
import discountTypeEnum from "../../../enums/modules/discountTypeEnum";
import alertService from "../../../services/alertService";
import targetService from "../../../services/targetService";
import ReceiptComponent from "./ReceiptComponent";
import PaymentComponent from "./PaymentComponent";
import PoscustomerComponent from './PosCustomerComponent';
import posPaymentMethodEnum from "../../../enums/modules/posPaymentMethodEnum";
import BarcodeProductComponent from "./BarcodeProductComponent.vue";

//extra category swiper
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';

export default {
  name: "PosComponent",
  components: {
    ReceiptComponent,
    LoadingComponent,
    ProductListComponent,
    PoscustomerComponent,
    BarcodeProductComponent,
    PaymentComponent,
    Swiper, SwiperSlide
  },
  data() {
    return {
      loading: {
        isActive: false,
      },
      order: {},
      discount: null,
      checkoutProps: {
        form: {
          customer_id: null,
          category: null,
          brand: null,
          discount: 0,
          total: 0,
          pos_payment_method: posPaymentMethodEnum.CASH,
          pos_payment_note: '',
          pos_payment_receive_amount: 0
        },
      },
      props: {
        search: {
          paginate: 1,
          order_column: "id",
          order_type: "asc",
          name: "",
          product_category_id: "",
          product_brand_id: "",
          status: statusEnum.ACTIVE
        },
      },
      searchProps: {
        paginate: 1,
        order_column: "id",
        order_type: "asc",
        status: statusEnum.ACTIVE
      },
      settings: {
        itemsToShow: 6.2,
        wrapAround: false,
        snapAlign: "start"
      },
      statusEnum: statusEnum,
      discountTypeEnum: discountTypeEnum,
      posPaymentMethodEnum: posPaymentMethodEnum,
      discountType: discountTypeEnum.PERCENTAGE,
      discountErrorMessage: "",
      form: {},

      //extra category swiper
      selectedLevel1: null,
      selectedLevel2: null,
      selectedLevel3: null,

      //extra hold order
      holdOrders: [],
      currentHoldOrderId: null,
      showHoldOrdersPanel: false,

      //extra
      showPaymentPanel: false,
      showCustomerPanel: false,
    }
  },
  computed: {
    setting: function () {
      return this.$store.getters['frontendSetting/lists'];
    },
    categories: function () {
      return this.$store.getters["productCategory/depthTrees"];
    },
    brands: function () {
      return this.$store.getters["productBrand/lists"];
    },
    products: function () {
      return this.$store.getters["product/lists"];
    },
    customers: function () {
      return this.$store.getters['user/lists'];
    },
    carts: function () {
      return this.$store.getters['posCart/lists'];
    },
    subtotal: function () {
      return this.$store.getters['posCart/subtotal'];
    },
    total: function () {
      return this.$store.getters['posCart/total'];
    },
    posCartDiscount: function () {
      return this.$store.getters['posCart/discount'];
    },
    totalTax: function () {
      return this.$store.getters['posCart/totalTax'];
    },
    posCartProducts: function () {
      return this.$store.getters['posCart/lists'];
    },
    posDiscount: function () {
      return this.$store.getters['posCart/discount'];
    },

    //extra category swiper
    flatCategories: function () {
        const flatten = (items, result = []) => {
            if (!items || !Array.isArray(items)) return result;
            items.forEach(item => {
                result.push(item);
                if (item.child && item.child.length > 0) {
                    flatten(item.child, result);
                }
                // also try 'children' key in case your store uses that
                if (item.children && item.children.length > 0) {
                    flatten(item.children, result);
                }
            });
            return result;
        };
        return flatten(this.categories);
    },
    level1Categories: function () {
        return this.categories.filter(c => c.segment === "" || c.segment === null);
    },
    level2Categories: function () {
        if (!this.selectedLevel1) return [];
        const parentIndex = this.categories.findIndex(c => c.id === this.selectedLevel1);
        if (parentIndex === -1) return [];
        const result = [];
        for (let i = parentIndex + 1; i < this.categories.length; i++) {
            const cat = this.categories[i];
            if (cat.segment === "" || cat.segment === null) break;
            if (cat.segment === "-") result.push(cat);
        }
        return result;
    },
    level3Categories: function () {
        if (!this.selectedLevel2) return [];
        const parentIndex = this.categories.findIndex(c => c.id === this.selectedLevel2);
        if (parentIndex === -1) return [];
        const result = [];
        for (let i = parentIndex + 1; i < this.categories.length; i++) {
            const cat = this.categories[i];
            if (cat.segment === "" || cat.segment === "-" || cat.segment === null) break;
            if (cat.segment === "--") result.push(cat);
        }
        return result;
    },

  },
  mounted() {
    this.productCategories();

    this.$store.dispatch("productCategory/depthTrees", this.searchProps).then((res) => {
    console.log("RAW categories from store:", JSON.stringify(this.categories).substring(0, 500));
    });

    this.productBrands();
    this.productList();
    try {
      this.customerList();

      this.loading.isActive = true;
      this.$store.dispatch("company/lists").then((res) => {
        this.company.name = res.data.data.company_name;
        this.company.email = res.data.data.company_email;
        this.company.phone = res.data.data.company_phone;
        this.company.address = res.data.data.company_address;
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    } catch (err) {
      this.loading.isActive = false;
    }

    //extra hold order
    window.addEventListener('pos-open-hold-orders', () => {
        this.loadHoldOrders();
        this.showHoldOrdersPanel = true;
    });
  },
  unmounted() { //extra hold order
      window.removeEventListener('pos-open-hold-orders', () => {});
  },
  methods: {
    hideTarget: function (id, cClass) {
      targetService.hideTarget(id, cClass);
    },
    openCanvas: function (id) {
      return appService.openCanvas(id);
    },
    closeCanvas: function (id) {
      return appService.closeCanvas(id);
    },
    onlyNumber: function (e) {
      return appService.onlyNumber(e);
    },
    floatNumber: function (e) {
      return appService.floatNumber(e);
    },
    currencyFormat(amount, decimal, currency, position) {
      return appService.currencyFormat(amount, decimal, currency, position);
    },
    floatFormat(amount, decimal) {
      return appService.floatFormat(amount, decimal);
    },
    reset: function () {
      this.props.search.name = "";
      this.checkoutProps.form.category = null;
      this.props.search.product_category_id = "";
      this.checkoutProps.form.brand = null;
      this.props.search.product_brand_id = "";
      this.productList();
    },
    search: function () {
      this.productList();
    },
    addCustomer: function () {
      //appService.modalShow("#customerModal");
      this.showCustomerPanel = true;
    },
    productCategories: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store.dispatch("productCategory/depthTrees", this.searchProps).then((res) => {
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    },
    productBrands: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store.dispatch("productBrand/lists", this.searchProps).then((res) => {
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    },
    customerList: function (id = null) {
      this.loading.isActive = true;
      this.$store.dispatch('user/lists', {
        order_column: 'id',
        order_type: 'asc',
        status: statusEnum.ACTIVE,
        role_id: roleEnum.CUSTOMER
      }).then((res) => {
        this.checkoutProps.form.customer_id = id === null ? res.data.data[0].id : id;
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    },
    productList: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store.dispatch("product/lists", this.props.search).then((res) => {
        this.loading.isActive = false;
      }).catch((err) => {
        this.loading.isActive = false;
      });
    },
    setCategory: function (id) {
      this.props.search.product_category_id = id;
      this.productList();
    },
    setBrand: function (id) {
      this.props.search.product_brand_id = id;
      this.productList();
    },
    quantityUp: function (id, product, e) {
      let quantity = e.target.value;

      if (quantity === 0 || quantity < 0 || quantity === "0") {
        quantity = 1;
      }
      if (quantity > product.stock) {
        quantity = product.stock
      }
      this.$store.dispatch('posCart/quantity', { id: id, status: quantity }).then().catch();
      this.checkoutProps.form.discount = 0;
      this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();
    },
    quantityIncrement: function (id, product) {
      let quantity = product.quantity;
      quantity++;
      if (quantity <= 0) {
        quantity = 1;
      }

      if (quantity > product.stock) {
        quantity--;
      }
      this.$store.dispatch('posCart/quantity', { id: id, status: quantity }).then().catch();
      this.checkoutProps.form.discount = 0;
      this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();
    },
    quantityDecrement: function (id, product) {
      let quantity = product.quantity;
      quantity--;
      if (quantity <= 0) {
        quantity = 1;
      }
      this.$store.dispatch('posCart/quantity', { id: id, status: quantity }).then().catch();
      this.checkoutProps.form.discount = 0;
      this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();
    },
    removeProduct: function (id) {
      this.$store.dispatch('posCart/remove', { id: id }).then().catch();
      this.checkoutProps.form.discount = 0;
      this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();
      if (this.carts.length === 0) {
        this.checkoutProps.form.pos_payment_method = posPaymentMethodEnum.CASH;
        this.checkoutProps.form.pos_payment_note = "";
        this.discountErrorMessage = "";

      }
    },
    applyDiscount: function () {
      this.discountErrorMessage = "";
      if (this.discountType === discountTypeEnum.FIXED) {
        if (this.subtotal < this.discount) {
          this.discountErrorMessage = this.$t('message.discount_fixed_error_message');
          this.checkoutProps.form.discount = 0;
          this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();
        } else {
          this.discountErrorMessage = "";
          this.checkoutProps.form.discount = parseFloat(+this.discount).toFixed(this.setting.site_digit_after_decimal_point);
          this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();
        }
      } else {
        if (this.discount > 100) {
          this.discountErrorMessage = this.$t('message.discount_error_message');
          this.checkoutProps.form.discount = 0;
          this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();
        } else {
          this.discountErrorMessage = "";
          this.checkoutProps.form.discount = parseFloat((this.subtotal * this.discount) / 100).toFixed(this.setting.site_digit_after_decimal_point);
          this.$store.dispatch('posCart/discount', this.checkoutProps.form.discount).then().catch();

        }
      }
    },
    resetCart: function () {
      this.$store.dispatch('posCart/resetCart').then(res => {
      }).catch();
      if (this.carts.length === 0) {
        this.checkoutProps.form.pos_payment_method = posPaymentMethodEnum.CASH;
        this.checkoutProps.form.pos_payment_note = "";
        this.discountErrorMessage = "";

      }
    },
    orderSubmit: function (data) {
      this.loading.isActive = true;
      this.form = {
        customer_id: this.checkoutProps.form.customer_id,
        subtotal: this.subtotal,
        discount: parseFloat(this.posCartDiscount),
        tax: this.totalTax,
        total: this.total,
        order_type: orderTypeEnum.POS,
        source: sourceEnum.POS,
        payment_method: paymentTypeEnum.CASH_ON_DELIVERY,
        pos_payment_method: data.pos_payment_method,
        pos_payment_note: data.pos_payment_method === posPaymentMethodEnum.CASH ? "" : data.pos_payment_note,
        pos_received_amount: data.pos_received_amount,
        products: JSON.stringify(this.posCartProducts)
      }
      this.$store.dispatch('posOrder/save', this.form).then(orderResponse => {
        this.$store.dispatch('posCart/resetCart').then(res => {
          this.checkoutProps.form.pos_payment_method = posPaymentMethodEnum.CASH;
          this.checkoutProps.form.pos_payment_note = "";
          this.discount = null;
          this.discountErrorMessage = "";
          this.loading.isActive = false;
        }).catch();
        alertService.success(this.$t('message.pos_order'));
        this.$store.dispatch('posOrder/show', orderResponse.data.data.id).then(res => {
          this.order = res.data.data;
          this.loading.isActive = false;
        }).catch((error) => {
          this.loading.isActive = false;
          alertService.error(error.response.data.message);
        });

        //appService.modalHide('#orderPayment');
        this.showPaymentPanel = false;

        appService.modalShow('#posReceiptModal');
      }).catch((err) => {
        this.loading.isActive = false;
        if (typeof err.response.data.errors === 'object') {
          _.forEach(err.response.data.errors, (error) => {
            alertService.error(error[0]);
          });
        }
      });
    },
    orderPayment: function () {
      //appService.modalShow('#orderPayment');
      this.showPaymentPanel = true;
    },
    totalProducts: function () {
      if (this.carts.length > 0) {
        let totalProduct = 0;
        this.carts.forEach(cart => {
          totalProduct += cart.quantity;
        });
        return totalProduct;
      }
    },
    onCustomverCreate: function (customerId) {
      appService.modalHide();
      this.customerList(customerId);
    },
    resetName: function () {
      this.props.search.name = "";
    },

    //extra category swiper
    allCategory: function () {
        this.selectedLevel1 = null;
        this.selectedLevel2 = null;
        this.selectedLevel3 = null;
        this.props.search.product_category_id = "";
        this.productList();
    },
    selectLevel1: function (id) {
        this.selectedLevel1 = id;
        this.selectedLevel2 = null;
        this.selectedLevel3 = null;
        this.props.search.product_category_id = id;
        this.productList();
    },
    selectLevel2: function (id) {
        this.selectedLevel2 = id;
        this.selectedLevel3 = null;
        if (id === null) {
            this.props.search.product_category_id = this.selectedLevel1;
        } else {
            this.props.search.product_category_id = id;
        }
        this.productList();
    },
    selectLevel3: function (id) {
        this.selectedLevel3 = id;
        if (id === null) {
            this.props.search.product_category_id = this.selectedLevel2;
        } else {
            this.props.search.product_category_id = id;
        }
        this.productList();
    },


    //extra hold order
    holdOrder: function () {
        if (this.carts.length === 0) {
            return alertService.error("Cart is empty");
        }

        const heldOrder = {
            id: 'hold_' + Date.now(),
            timestamp: Date.now(),
            items: JSON.parse(JSON.stringify(this.carts)),
            customer: this.checkoutProps.form.customer_id,
            customerName: this.customers.find(c => c.id === this.checkoutProps.form.customer_id)?.name || '',
            token: this.checkoutProps.form.token || '',
            subtotal: this.subtotal,
            discount: this.posCartDiscount || 0,
        };

        try {
            const saved = JSON.parse(localStorage.getItem('foodking_held_orders') || '[]');
            saved.push(heldOrder);
            localStorage.setItem('foodking_held_orders', JSON.stringify(saved));
        } catch (e) {
            console.error('Error saving hold order', e);
        }

        this.resetCart();
        this.loadHoldOrders();
        window.dispatchEvent(new Event('hold-orders-updated'));
        alertService.success("Order held successfully");
    },
    loadHoldOrders: function () {
        try {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const saved = localStorage.getItem('foodking_held_orders');
            if (saved) {
                const all = JSON.parse(saved);
                this.holdOrders = all.filter(order => new Date(order.timestamp) >= today);
            } else {
                this.holdOrders = [];
            }
        } catch (e) {
            this.holdOrders = [];
        }
    },

    loadHoldOrder: function (orderId) {
    const order = this.holdOrders.find(o => o.id === orderId);
    if (!order) return;

    // Reset cart first, then directly set the store's cart items
    this.$store.dispatch('posCart/resetCart').then(() => {

        // Directly commit the saved items into the store state
        this.$store.state.posCart.lists = JSON.parse(JSON.stringify(order.items));

        // Restore customer
        this.checkoutProps.form.customer_id = order.customer;

        // Remove from localStorage
        try {
            let saved = JSON.parse(localStorage.getItem('foodking_held_orders') || '[]');
            saved = saved.filter(o => o.id !== orderId);
            localStorage.setItem('foodking_held_orders', JSON.stringify(saved));
        } catch (e) {}

        // Remove from local list, update navbar badge, close panel
        this.holdOrders = this.holdOrders.filter(o => o.id !== orderId);
        window.dispatchEvent(new Event('hold-orders-updated'));
        this.showHoldOrdersPanel = false;
        alertService.success("Order loaded successfully");
    });
},

    deleteHoldOrder: function (orderId) {
        try {
            let saved = JSON.parse(localStorage.getItem('foodking_held_orders') || '[]');
            saved = saved.filter(o => o.id !== orderId);
            localStorage.setItem('foodking_held_orders', JSON.stringify(saved));
            this.holdOrders = this.holdOrders.filter(o => o.id !== orderId);
        } catch (e) {
            console.error('Error deleting hold order', e);
        }
    },

    formatHoldTime: function (timestamp) {
        return new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    },
  }
}
</script>