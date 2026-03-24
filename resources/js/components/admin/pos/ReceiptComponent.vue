<template>
    <div id="posReceiptModal" class="modal">
        <div class="modal-dialog max-w-[340px] rounded-none">
            <div class="modal-header hidden-print">
                <button type="button" @click="reset"
                    class="modal-close flex items-center justify-center gap-1.5 py-2 px-4 rounded bg-[#FB4E4E]">
                    <i class="lab lab-fill-close-circle lab-font-size-16 text-white"></i>
                    <span class="text-xs leading-5 capitalize text-white">{{ $t('button.close') }}</span>
                </button>
                <button type="button" @click="handlePrint"
                    class="flex items-center justify-center gap-1.5 py-2 px-4 rounded bg-[#1AB759]">
                    <i class="lab lab-fill-printer lab-font-size-16 text-white"></i>
                    <span class="text-xs leading-5 capitalize text-white">{{ $t('button.print_invoice') }}</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Hidden trigger for v-print fallback (no physical printer configured) -->
                <button class="print-trigger hidden-print" v-print="printObj" style="display:none;"></button>
                <PosOrderReceiptListComponent :order="order" />
            </div>
        </div>
    </div>
</template>

<script>
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import PosOrderReceiptListComponent from "../posOrders/PosOrderReceiptListComponent";
import print from "vue3-print-nb";

export default {
    name: "ReceiptComponent",
    components: {
        PosOrderReceiptListComponent,
    },
    props: {
        order: Object
    },
    data() {
        return {
            printObj: {
                id: "receipt-content",
                popTitle: this.$t("menu.order_receipt"),
            },
            RECEIPT_PRINTER_KEY: 'receipt_printer',
            usbDevice: null,
        }
    },
    computed: {
        company: function () {
            return this.$store.getters['company/lists'];
        },
    },
    mounted() {
        this.$store.dispatch("company/lists").then().catch();
        this.initPrinter();
    },
    directives: { print },
    methods: {
        reset: function () {
            appService.modalHide();
        },

        handlePrint: async function () {
            const printer = JSON.parse(localStorage.getItem(this.RECEIPT_PRINTER_KEY));
            if (!printer) {
                // No physical printer configured — use browser print scoped to receipt
                this.$el.querySelector('.print-trigger').click();
            } else if (printer.type === 'usb' && this.usbDevice) {
                await this.printViaUSB();
            } else if (printer.type === 'network' && printer.ip) {
                await this.printViaNetwork(printer.ip, printer.port || 9100);
            } else {
                this.$el.querySelector('.print-trigger').click();
            }
        },

        initPrinter: async function () {
            try {
                const printer = JSON.parse(localStorage.getItem(this.RECEIPT_PRINTER_KEY));
                if (!printer || printer.type !== 'usb') return;
                const devices = await navigator.usb.getDevices();
                const device = devices.find(d =>
                    d.vendorId === printer.vendorId && d.productId === printer.productId
                );
                if (device) {
                    await device.open();
                    await device.selectConfiguration(1);
                    await device.claimInterface(0);
                    this.usbDevice = device;
                }
            } catch (err) {
                console.error('Printer init failed:', err);
            }
        },

        printViaNetwork: async function (printerIp, printerPort = 9100) {
            try {
                const receiptEl = document.getElementById('receipt-content');
                if (!receiptEl) return alertService.error('Receipt element not found');
                const html2canvas = (await import('html2canvas')).default;
                const canvas = await html2canvas(receiptEl, { scale: 2, backgroundColor: '#ffffff', useCORS: true });
                const imageData = canvas.getContext('2d').getImageData(0, 0, canvas.width, canvas.height);
                const encoder = new TextEncoder();
                const init    = encoder.encode('\x1B\x40');
                const cut     = encoder.encode('\x1D\x56\x00');
                const escpos  = this.convertImageToESCPOS(imageData);
                const allData = [...init, ...escpos, ...cut];
                const res = await fetch('/api/print-receipt', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ip: printerIp, port: printerPort, data: allData })
                });
                const result = await res.json();
                if (result.success) alertService.success('Receipt printed!');
                else alertService.error('Print failed: ' + result.error);
            } catch (err) {
                alertService.error('Print failed: ' + err.message);
            }
        },

        printViaUSB: async function () {
            try {
                const receiptEl = document.getElementById('receipt-content');
                if (!receiptEl) return alertService.error('Receipt element not found');
                const html2canvas = (await import('html2canvas')).default;
                const canvas = await html2canvas(receiptEl, { scale: 2, backgroundColor: '#ffffff', useCORS: true });
                const imageData = canvas.getContext('2d').getImageData(0, 0, canvas.width, canvas.height);
                const encoder = new TextEncoder();
                const init    = encoder.encode('\x1B\x40');
                const cut     = encoder.encode('\x1D\x56\x00');
                const escpos  = this.convertImageToESCPOS(imageData);
                const allData = new Uint8Array([...init, ...escpos, ...cut]);
                await this.usbDevice.transferOut(1, allData);
                alertService.success('Receipt printed!');
            } catch (err) {
                alertService.error('USB print failed: ' + err.message);
            }
        },

        convertImageToESCPOS: function (imageData) {
            const { width, height, data } = imageData;
            const bytes = [];
            const lineHeight = 24;
            for (let y = 0; y < height; y += lineHeight) {
                const actualLines = Math.min(lineHeight, height - y);
                bytes.push(0x1B, 0x2A, 33,
                    width & 0xFF, (width >> 8) & 0xFF);
                for (let x = 0; x < width; x++) {
                    for (let b = 0; b < 3; b++) {
                        let byte = 0;
                        for (let bit = 0; bit < 8; bit++) {
                            const row = y + b * 8 + bit;
                            if (row < height) {
                                const idx = (row * width + x) * 4;
                                const gray = 0.299 * data[idx] + 0.587 * data[idx+1] + 0.114 * data[idx+2];
                                if (gray < 128) byte |= (1 << (7 - bit));
                            }
                        }
                        bytes.push(byte);
                    }
                }
                bytes.push(0x0A);
            }
            return bytes;
        },
    },
}
</script>

<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>