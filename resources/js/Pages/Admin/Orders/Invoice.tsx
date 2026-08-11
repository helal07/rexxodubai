import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Printer, MessageCircle, List, Building2, Phone, Mail, MapPin } from 'lucide-react';

interface OrderItem {
    id: number;
    product_name: string;
    size: string | null;
    quantity: number;
    unit_price: string;
    total_price: string;
}

interface Order {
    id: number;
    order_number: string;
    customer_name: string;
    customer_email: string;
    customer_phone: string;
    shipping_address: string;
    city: string;
    status: string;
    payment_status: string;
    payment_method: string;
    subtotal: string;
    shipping_cost: string;
    discount_amount: string;
    total_amount: string;
    created_at: string;
    items: OrderItem[];
}

interface InvoiceProps {
    order: Order;
    siteSettings: Record<string, string>;
}

export default function Invoice({ order, siteSettings }: InvoiceProps) {
    const handlePrint = () => {
        window.print();
    };

    // Force Vite HMR to pick up the latest changes
    console.log("Invoice loaded with settings:", siteSettings);

    const currencySymbol = siteSettings['currency'] || 'USD ($)';
    const symbolMatch = currencySymbol.match(/\((.*?)\)/);
    const symbol = symbolMatch ? symbolMatch[1] : (currencySymbol.split(' ')[0] || '$');

    const handleWhatsApp = () => {
        // format phone for whatsapp (assume BD +880 if 11 digits starting with 01)
        let phone = order.customer_phone.replace(/\D/g, '');
        if (phone.length === 11 && phone.startsWith('01')) {
            phone = '88' + phone;
        }

        const message = `Hello ${order.customer_name},\n\nThis is an invoice for your recent order from *${siteSettings['site_name'] || 'Our Store'}*.\n\n*Order Number:* ${order.order_number}\n*Total Amount:* ${symbol}${order.total_amount}\n*Payment Status:* ${order.payment_status.toUpperCase()}\n\nThank you for your purchase!`;
        const waLink = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
        window.open(waLink, '_blank');
    };

    const logoUrl = siteSettings['site_logo'] || siteSettings['logo_url'] || null;

    return (
        <AdminLayout activePage="orders">
            <Head title={`Invoice #${order.order_number} — Admin`} />

            {/* Print Styles */}
            <style dangerouslySetInnerHTML={{__html: `
                @media print {
                    body * {
                        visibility: hidden;
                    }
                    #invoice-print-area, #invoice-print-area * {
                        visibility: visible;
                    }
                    #invoice-print-area {
                        position: absolute !important;
                        left: 0 !important;
                        top: 0 !important;
                        width: 100% !important;
                        background-color: white !important;
                        padding: 40px !important;
                        margin: 0 !important;
                        box-shadow: none !important;
                    }
                    .no-print {
                        display: none !important;
                    }
                    /* Reset AdminLayout paddings for print */
                    main, .admin-main-content {
                        padding: 0 !important;
                        margin: 0 !important;
                    }
                }
            `}} />

            {/* ACTION BUTTONS (NO PRINT) */}
            <div className="no-print bg-white border border-slate-200 p-4 rounded-xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 sticky top-4 z-10">
                <div>
                    <h2 className="text-[18px] font-serif font-bold text-[#0f172a]">
                        Invoice Details
                    </h2>
                    <p className="text-[13px] text-slate-500">View, print, or share the invoice for Order #{order.order_number}</p>
                </div>
                <div className="flex items-center gap-3">
                    <Link
                        href="/admin/orders"
                        className="px-4 py-2 text-[13px] font-bold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors flex items-center gap-2"
                    >
                        <List className="w-4 h-4" /> All Orders
                    </Link>
                    <button
                        onClick={handleWhatsApp}
                        className="px-4 py-2 text-[13px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition-colors flex items-center gap-2"
                    >
                        <MessageCircle className="w-4 h-4" /> WhatsApp
                    </button>
                    <button
                        onClick={handlePrint}
                        className="px-4 py-2 text-[13px] font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-md shadow-indigo-600/20"
                    >
                        <Printer className="w-4 h-4" /> Print Invoice
                    </button>
                </div>
            </div>

            {/* INVOICE PAPER AREA */}
            <div className="flex justify-center w-full mb-12">
                <div id="invoice-print-area" className="bg-white w-full max-w-[210mm] shadow-sm border border-slate-200 rounded-xl p-8 md:p-12 text-slate-800 mx-auto">
                    {/* Header */}
                    <div className="flex justify-between items-start border-b-2 border-slate-100 pb-8 mb-8">
                        <div>
                            {logoUrl ? (
                                <img src={logoUrl} alt="Logo" className="h-12 object-contain mb-4" />
                            ) : (
                                <div className="text-2xl font-serif font-bold text-slate-900 mb-4 flex items-center gap-2">
                                    <Building2 className="w-6 h-6 text-indigo-600" />
                                    {siteSettings['site_name'] || siteSettings['siteName'] || 'Our Store'}
                                </div>
                            )}
                            <div className="space-y-1 text-[13px] text-slate-500 max-w-xs">
                                <div className="flex items-start gap-1.5">
                                    <MapPin className="w-4 h-4 mt-0.5 shrink-0" />
                                    <span>{siteSettings['address'] || siteSettings['site_address'] || '123 Business Avenue, City, Country'}</span>
                                </div>
                                <div className="flex items-center gap-1.5">
                                    <Phone className="w-4 h-4 shrink-0" />
                                    <span>{siteSettings['contactPhone'] || siteSettings['phone'] || siteSettings['site_phone'] || '+880 1234 567890'}</span>
                                </div>
                                <div className="flex items-center gap-1.5">
                                    <Mail className="w-4 h-4 shrink-0" />
                                    <span>{siteSettings['contactEmail'] || siteSettings['email'] || siteSettings['site_email'] || 'contact@store.com'}</span>
                                </div>
                            </div>
                        </div>
                        <div className="text-right">
                            <h1 className="text-4xl font-serif font-black text-indigo-600 tracking-tight mb-2 uppercase">Invoice</h1>
                            <div className="text-[13px] text-slate-500 space-y-1">
                                <p><span className="font-bold text-slate-700">Invoice No:</span> {order.order_number}</p>
                                <p><span className="font-bold text-slate-700">Date:</span> {new Date(order.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                <p className="mt-2">
                                    <span className={`inline-block px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider ${order.payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}`}>
                                        {order.payment_status}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Customer Info */}
                    <div className="mb-10 p-5 bg-slate-50 rounded-xl border border-slate-100">
                        <h3 className="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Invoice To</h3>
                        <div className="text-[14px]">
                            <p className="font-bold text-slate-900 text-base mb-1">{order.customer_name}</p>
                            <p className="text-slate-600 mb-1">{order.customer_phone}</p>
                            {order.customer_email && <p className="text-slate-600 mb-1">{order.customer_email}</p>}
                            <p className="text-slate-600 max-w-sm mt-2">{order.shipping_address}</p>
                            {order.city && <p className="text-slate-600">{order.city}</p>}
                        </div>
                    </div>

                    {/* Items Table */}
                    <div className="mb-8">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="border-b-2 border-slate-800">
                                    <th className="py-3 px-2 text-[12px] font-bold text-slate-900 uppercase">Item Description</th>
                                    <th className="py-3 px-2 text-[12px] font-bold text-slate-900 uppercase text-center w-24">Qty</th>
                                    <th className="py-3 px-2 text-[12px] font-bold text-slate-900 uppercase text-right w-32">Price</th>
                                    <th className="py-3 px-2 text-[12px] font-bold text-slate-900 uppercase text-right w-32">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {order.items.map((item, index) => (
                                    <tr key={item.id} className="border-b border-slate-100">
                                        <td className="py-4 px-2">
                                            <p className="font-bold text-slate-900 text-[14px]">{item.product_name}</p>
                                            {item.size && item.size !== 'Base' && (
                                                <p className="text-[12px] text-slate-500 mt-1 uppercase tracking-wider">{item.size}</p>
                                            )}
                                        </td>
                                        <td className="py-4 px-2 text-center text-[14px] text-slate-700">
                                            {item.quantity}
                                        </td>
                                        <td className="py-4 px-2 text-right text-[14px] text-slate-700 font-mono">
                                            {symbol}{Number(item.unit_price).toFixed(2)}
                                        </td>
                                        <td className="py-4 px-2 text-right text-[14px] font-bold text-slate-900 font-mono">
                                            {symbol}{Number(item.total_price).toFixed(2)}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {/* Totals */}
                    <div className="flex justify-end">
                        <div className="w-1/2">
                            <div className="space-y-3 text-[14px]">
                                <div className="flex justify-between text-slate-600 px-2">
                                    <span>Subtotal</span>
                                    <span className="font-mono">{symbol}{Number(order.subtotal).toFixed(2)}</span>
                                </div>
                                <div className="flex justify-between text-slate-600 px-2">
                                    <span>Shipping Cost</span>
                                    <span className="font-mono">{symbol}{Number(order.shipping_cost).toFixed(2)}</span>
                                </div>
                                {Number(order.discount_amount) > 0 && (
                                    <div className="flex justify-between text-rose-600 px-2">
                                        <span>Discount</span>
                                        <span className="font-mono">-{symbol}{Number(order.discount_amount).toFixed(2)}</span>
                                    </div>
                                )}
                                <div className="flex justify-between items-center text-[18px] font-bold text-indigo-700 border-t-2 border-slate-800 pt-3 px-2 mt-2">
                                    <span>Grand Total</span>
                                    <span className="font-mono">{symbol}{Number(order.total_amount).toFixed(2)}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Footer */}
                    <div className="mt-20 pt-8 border-t border-slate-200 text-center text-[12px] text-slate-500">
                        <p className="font-bold text-slate-700 mb-1">Thank you for your business!</p>
                        <p>If you have any questions about this invoice, please contact us at {siteSettings['contactPhone'] || siteSettings['phone'] || siteSettings['site_phone'] || 'our support line'}.</p>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
