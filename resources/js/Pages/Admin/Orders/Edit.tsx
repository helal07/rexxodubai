import React, { useState, useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    ShoppingCart,
    User,
    ShoppingBag,
    Plus,
    Minus,
    Trash2,
    RotateCcw,
    List,
    Save
} from 'lucide-react';

interface ProductVariant {
    id: number;
    name: string;
    price: string;
    stock: number;
}

interface Product {
    id: number;
    name: string;
    price: number;
    stock: number;
    variants: ProductVariant[];
}

interface OrderItem {
    id?: number;
    product_id: number;
    product_name: string;
    size: string | null;
    unit_price: number;
    quantity: number;
    total_price: number;
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
    shipping_cost: number;
    discount_amount: number;
    items: OrderItem[];
}

interface EditOrderProps {
    order: Order;
    products: Product[];
}

export default function EditOrder({ order, products = [] }: EditOrderProps) {
    const [customerName, setCustomerName] = useState(order.customer_name);
    const [customerPhone, setCustomerPhone] = useState(order.customer_phone || '');
    const [customerEmail, setCustomerEmail] = useState(order.customer_email || '');
    const [customerAddress, setCustomerAddress] = useState(order.shipping_address);
    const [city, setCity] = useState(order.city || '');
    const [status, setStatus] = useState(order.status);
    const [paymentStatus, setPaymentStatus] = useState(order.payment_status);
    const [paymentMethod, setPaymentMethod] = useState(order.payment_method);
    const [shippingCost, setShippingCost] = useState<number>(Number(order.shipping_cost) || 0);
    const [discount, setDiscount] = useState<number>(Number(order.discount_amount) || 0);

    const [cart, setCart] = useState<OrderItem[]>(
        order.items.map(i => ({
            id: i.id,
            product_id: i.product_id,
            product_name: i.product_name,
            size: i.size,
            unit_price: Number(i.unit_price),
            quantity: i.quantity,
            total_price: Number(i.total_price),
        }))
    );

    const [selectedProductVal, setSelectedProductVal] = useState('');
    const [submitting, setSubmitting] = useState(false);

    // Calculations
    const cartSubtotal = cart.reduce((acc, item) => acc + item.unit_price * item.quantity, 0);
    const totalPayable = Math.max(0, cartSubtotal + shippingCost - discount);

    const handleAddProductToCart = () => {
        if (!selectedProductVal) return;
        const [pIdStr, vName, pPriceStr] = selectedProductVal.split('|');
        const pId = Number(pIdStr);
        const price = Number(pPriceStr);
        const product = products.find(p => p.id === pId);

        if (product) {
            setCart(prev => {
                const existingIndex = prev.findIndex(item => item.product_id === pId && item.size === vName);
                if (existingIndex > -1) {
                    const updated = [...prev];
                    updated[existingIndex].quantity += 1;
                    return updated;
                } else {
                    return [...prev, { 
                        product_id: pId, 
                        product_name: product.name, 
                        size: vName, 
                        unit_price: price, 
                        quantity: 1, 
                        total_price: price 
                    }];
                }
            });
        }
        setSelectedProductVal('');
    };

    const updateQuantity = (index: number, delta: number) => {
        setCart(prev => {
            const updated = [...prev];
            const newQty = updated[index].quantity + delta;
            if (newQty > 0) {
                updated[index].quantity = newQty;
            }
            return updated;
        });
    };

    const removeCartItem = (index: number) => {
        setCart(prev => prev.filter((_, i) => i !== index));
    };

    const handleUpdateSale = (e: React.FormEvent) => {
        e.preventDefault();
        if (cart.length === 0) {
            alert('Please add at least one product to the cart before completing.');
            return;
        }

        setSubmitting(true);
        router.put(`/admin/orders/${order.id}`, {
            customer_name: customerName,
            customer_phone: customerPhone,
            customer_email: customerEmail,
            shipping_address: customerAddress,
            city: city,
            status: status,
            payment_status: paymentStatus,
            payment_method: paymentMethod,
            shipping_cost: shippingCost,
            discount_amount: discount,
            items: cart.map(item => ({
                product_id: item.product_id,
                size: item.size,
                quantity: item.quantity,
                unit_price: item.unit_price,
            })),
        }, {
            onFinish: () => setSubmitting(false),
        });
    };

    return (
        <AdminLayout activePage="orders">
            <Head title={`Edit Order #${order.order_number} — Admin`} />

            {/* TOP POS HEADER BANNER */}
            <div className="bg-white/90 border border-emerald-500/30 p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div className="flex items-center gap-3.5">
                    <div className="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white flex items-center justify-center shadow-md shadow-emerald-600/20">
                        <ShoppingCart className="w-5 h-5" />
                    </div>
                    <div>
                        <div className="flex items-center gap-2">
                            <span className="text-[10px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                EDIT ORDER
                            </span>
                        </div>
                        <h2 className="text-[18px] font-serif font-bold text-[#0f172a] uppercase">
                            Order #{order.order_number}
                        </h2>
                    </div>
                </div>

                <div className="flex items-center gap-2">
                    <Link
                        href="/admin/orders"
                        className="px-3.5 py-2 text-[12px] font-bold text-[#0284c7] bg-sky-50 border border-sky-200 rounded-xl hover:bg-sky-100 transition-colors flex items-center gap-1.5 shadow-2xs"
                    >
                        <List className="w-3.5 h-3.5" /> All Orders
                    </Link>
                </div>
            </div>

            {/* MAIN 2-COLUMN GRID */}
            <form onSubmit={handleUpdateSale} className="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
                {/* LEFT COLUMN: Customer & Cart Builder (7 COLS) */}
                <div className="lg:col-span-7 space-y-6">
                    {/* 1. CUSTOMER INFORMATION */}
                    <div className="bg-white/90 border border-slate-200 p-6 rounded-2xl shadow-xs space-y-4">
                        <div className="flex justify-between items-center border-b border-slate-100 pb-3">
                            <h3 className="text-[13px] font-bold text-[#0f172a] uppercase flex items-center gap-2">
                                <User className="w-4 h-4 text-emerald-600" /> Customer Information
                            </h3>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label className="text-[11px] font-bold uppercase text-slate-500 block mb-1">Name</label>
                                <input
                                    type="text"
                                    required
                                    value={customerName}
                                    onChange={e => setCustomerName(e.target.value)}
                                    className="w-full border border-slate-300 p-2.5 rounded-xl text-[13px] font-medium focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none"
                                />
                            </div>
                            <div>
                                <label className="text-[11px] font-bold uppercase text-slate-500 block mb-1">Phone</label>
                                <input
                                    type="text"
                                    required
                                    value={customerPhone}
                                    onChange={e => setCustomerPhone(e.target.value)}
                                    className="w-full border border-slate-300 p-2.5 rounded-xl text-[13px] font-medium focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none"
                                />
                            </div>
                            <div className="md:col-span-2">
                                <label className="text-[11px] font-bold uppercase text-slate-500 block mb-1">Address</label>
                                <textarea
                                    required
                                    value={customerAddress}
                                    onChange={e => setCustomerAddress(e.target.value)}
                                    className="w-full border border-slate-300 p-2.5 rounded-xl text-[13px] font-medium focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none min-h-[60px]"
                                />
                            </div>
                        </div>
                    </div>

                    {/* 2. CART BUILDER */}
                    <div className="bg-white/90 border border-slate-200 p-6 rounded-2xl shadow-xs space-y-4">
                        <div className="flex justify-between items-center border-b border-slate-100 pb-3">
                            <h3 className="text-[13px] font-bold text-[#0f172a] uppercase flex items-center gap-2">
                                <ShoppingBag className="w-4 h-4 text-emerald-600" /> Order Items
                            </h3>
                        </div>

                        <div className="flex gap-2">
                            <select
                                value={selectedProductVal}
                                onChange={e => setSelectedProductVal(e.target.value)}
                                className="flex-1 border border-slate-300 p-2.5 rounded-xl text-[13px] font-medium focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none bg-white text-slate-800"
                            >
                                <option value="">-- Add Product / Variant --</option>
                                {products.map(p => (
                                    <optgroup key={p.id} label={p.name}>
                                        <option value={`${p.id}|Base|${p.price}`}>{p.name} (Base) - ৳{p.price}</option>
                                        {p.variants?.map(v => (
                                            <option key={v.id} value={`${p.id}|${v.name}|${v.price || p.price}`}>
                                                {p.name} ({v.name}) - ৳{v.price || p.price}
                                            </option>
                                        ))}
                                    </optgroup>
                                ))}
                            </select>
                            <button
                                type="button"
                                onClick={handleAddProductToCart}
                                className="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold transition-colors cursor-pointer flex items-center gap-2"
                            >
                                <Plus className="w-4 h-4" /> Add
                            </button>
                        </div>

                        {/* Cart List */}
                        <div className="border border-slate-200 rounded-xl overflow-hidden bg-slate-50 mt-4">
                            <table className="w-full text-left border-collapse">
                                <thead className="bg-slate-100 border-b border-slate-200">
                                    <tr>
                                        <th className="p-3 text-[11px] font-bold uppercase text-slate-500">Item</th>
                                        <th className="p-3 text-[11px] font-bold uppercase text-slate-500 w-24 text-center">Qty</th>
                                        <th className="p-3 text-[11px] font-bold uppercase text-slate-500 text-right w-24">Price</th>
                                        <th className="p-3 text-[11px] font-bold uppercase text-slate-500 text-right w-24">Total</th>
                                        <th className="p-3 w-12 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {cart.map((item, i) => (
                                        <tr key={i} className="border-b border-slate-100 last:border-0 bg-white">
                                            <td className="p-3">
                                                <div className="font-bold text-[13px] text-slate-800">{item.product_name}</div>
                                                {item.size && item.size !== 'Base' && (
                                                    <div className="text-[11px] text-slate-500 uppercase tracking-widest">{item.size}</div>
                                                )}
                                            </td>
                                            <td className="p-3 text-center">
                                                <div className="flex items-center justify-center gap-1.5 bg-slate-100 rounded-lg p-1 border border-slate-200">
                                                    <button type="button" onClick={() => updateQuantity(i, -1)} className="p-1 bg-white hover:bg-slate-50 rounded-md border border-slate-200 shadow-sm cursor-pointer"><Minus className="w-3 h-3 text-slate-600" /></button>
                                                    <span className="text-[12px] font-bold w-6 text-center">{item.quantity}</span>
                                                    <button type="button" onClick={() => updateQuantity(i, 1)} className="p-1 bg-white hover:bg-slate-50 rounded-md border border-slate-200 shadow-sm cursor-pointer"><Plus className="w-3 h-3 text-slate-600" /></button>
                                                </div>
                                            </td>
                                            <td className="p-3 text-right text-[13px] font-mono font-medium text-slate-600">
                                                ৳{item.unit_price}
                                            </td>
                                            <td className="p-3 text-right text-[13px] font-mono font-bold text-[#0f172a]">
                                                ৳{item.unit_price * item.quantity}
                                            </td>
                                            <td className="p-3 text-center">
                                                <button type="button" onClick={() => removeCartItem(i)} className="text-rose-500 hover:text-rose-700 p-1.5 hover:bg-rose-50 rounded-lg cursor-pointer">
                                                    <Trash2 className="w-4 h-4" />
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                    {cart.length === 0 && (
                                        <tr><td colSpan={5} className="p-8 text-center text-[12px] text-slate-400 font-medium">Cart is empty</td></tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* RIGHT COLUMN: Order Status & Checkout (5 COLS) */}
                <div className="lg:col-span-5 space-y-6">
                    <div className="bg-white/90 border border-slate-200 p-6 rounded-2xl shadow-xs space-y-4 sticky top-6">
                        
                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <label className="text-[11px] font-bold uppercase text-slate-500 block mb-1">Order Status</label>
                                <select value={status} onChange={e => setStatus(e.target.value)} className="w-full border border-slate-300 p-2.5 rounded-xl text-[13px] font-bold">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label className="text-[11px] font-bold uppercase text-slate-500 block mb-1">Payment Status</label>
                                <select value={paymentStatus} onChange={e => setPaymentStatus(e.target.value)} className="w-full border border-slate-300 p-2.5 rounded-xl text-[13px] font-bold">
                                    <option value="unpaid">Unpaid</option>
                                    <option value="paid">Paid</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                        </div>

                        <div className="border-t border-slate-100 pt-4 mt-2">
                            <div className="flex justify-between text-[13px] text-slate-600 mb-2">
                                <span>Subtotal</span>
                                <span className="font-mono">৳{cartSubtotal}</span>
                            </div>
                            <div className="flex justify-between items-center text-[13px] text-slate-600 mb-2">
                                <span>Shipping Cost</span>
                                <div className="flex items-center gap-1 w-24">
                                    <span className="font-mono">৳</span>
                                    <input type="number" value={shippingCost} onChange={e => setShippingCost(Number(e.target.value))} className="w-full text-right p-1 border border-slate-300 rounded-md text-[12px] font-mono outline-none focus:border-emerald-500" />
                                </div>
                            </div>
                            <div className="flex justify-between items-center text-[13px] text-slate-600 mb-4">
                                <span>Discount</span>
                                <div className="flex items-center gap-1 w-24">
                                    <span className="font-mono">৳</span>
                                    <input type="number" value={discount} onChange={e => setDiscount(Number(e.target.value))} className="w-full text-right p-1 border border-slate-300 rounded-md text-[12px] font-mono outline-none focus:border-emerald-500" />
                                </div>
                            </div>
                            <div className="flex justify-between items-center text-[16px] font-bold text-[#0f172a] pt-4 border-t border-slate-200">
                                <span>Total Payable</span>
                                <span className="font-mono text-emerald-700">৳{totalPayable}</span>
                            </div>
                        </div>

                        <div className="pt-2">
                            <button
                                type="submit"
                                disabled={submitting}
                                className="w-full bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white px-4 py-3.5 rounded-xl text-[14px] font-bold shadow-lg shadow-emerald-600/30 transition-all active:scale-95 flex items-center justify-center gap-2"
                            >
                                <Save className="w-5 h-5" />
                                {submitting ? 'Updating...' : 'Update Order'}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </AdminLayout>
    );
}
