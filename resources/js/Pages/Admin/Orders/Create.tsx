import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    ShoppingCart,
    User,
    UserPlus,
    PackagePlus,
    ShoppingBag,
    Plus,
    Minus,
    Trash2,
    CreditCard,
    Receipt,
    CheckCircle,
    Printer,
    RotateCcw,
    List,
    X
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
    variants?: ProductVariant[];
}

interface Customer {
    id: number;
    name: string;
    phone: string;
    address: string;
}

interface CartItem {
    product_id: number;
    name: string;
    size: string;
    price: number;
    quantity: number;
}

interface CreateOrderProps {
    products: Product[];
    customers: Customer[];
}

export default function CreateOrder({ products = [], customers = [] }: CreateOrderProps) {
    // Step 1: Customer State
    const [selectedCustomerVal, setSelectedCustomerVal] = useState('Walk-in Customer|01700000000|Store Counter, Dhaka');
    const [customerName, setCustomerName] = useState('Walk-in Customer');
    const [customerPhone, setCustomerPhone] = useState('01700000000');
    const [customerAddress, setCustomerAddress] = useState('Store Counter, Dhaka');

    // Quick Add Customer Modal State
    const [showQuickCustomerModal, setShowQuickCustomerModal] = useState(false);
    const [newCustName, setNewCustName] = useState('');
    const [newCustPhone, setNewCustPhone] = useState('');
    const [newCustAddress, setNewCustAddress] = useState('');

    // Step 2: Cart State
    const [cart, setCart] = useState<CartItem[]>([]);
    const [searchQuery, setSearchQuery] = useState('');
    const [isDropdownOpen, setIsDropdownOpen] = useState(false);
    const [orderNotes, setOrderNotes] = useState('');

    // Step 3: Payment & Logistics State
    const [paymentMethod, setPaymentMethod] = useState('Cash on Delivery');
    const [courierPartner, setCourierPartner] = useState('Pathao Courier API');
    const [shippingCost, setShippingCost] = useState<number>(60);
    const [discount, setDiscount] = useState<number>(0);

    const [submitting, setSubmitting] = useState(false);

    // Calculations
    const cartSubtotal = cart.reduce((acc, item) => acc + item.price * item.quantity, 0);
    const totalPayable = Math.max(0, cartSubtotal + shippingCost - discount);

    const handleCustomerChange = (val: string) => {
        setSelectedCustomerVal(val);
        const parts = val.split('|');
        if (parts.length >= 3) {
            setCustomerName(parts[0]);
            setCustomerPhone(parts[1]);
            setCustomerAddress(parts[2]);
        }
    };

    const handleQuickAddCustomerSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!newCustName) return;

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const res = await fetch('/admin/api/customers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    name: newCustName,
                    phone: newCustPhone,
                    address: newCustAddress,
                }),
            });

            if (res.ok) {
                setCustomerName(newCustName);
                setCustomerPhone(newCustPhone);
                setCustomerAddress(newCustAddress);
                setSelectedCustomerVal(`${newCustName}|${newCustPhone}|${newCustAddress}`);
                setShowQuickCustomerModal(false);
                setNewCustName('');
                setNewCustPhone('');
                setNewCustAddress('');
            } else {
                alert('Saved customer locally for this transaction.');
                setCustomerName(newCustName);
                setCustomerPhone(newCustPhone);
                setCustomerAddress(newCustAddress);
                setShowQuickCustomerModal(false);
            }
        } catch (err) {
            setCustomerName(newCustName);
            setCustomerPhone(newCustPhone);
            setCustomerAddress(newCustAddress);
            setShowQuickCustomerModal(false);
        }
    };

    const handleAddProductToCart = (pId: number, vName: string, price: number) => {
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
                        name: product.name, 
                        size: vName, 
                        price: price, 
                        quantity: 1
                    }];
                }
            });
        }
        setSearchQuery('');
        setIsDropdownOpen(false);
    };

    const updateQuantity = (product_id: number, size: string, delta: number) => {
        setCart(prev =>
            prev
                .map(item => {
                    if (item.product_id === product_id && item.size === size) {
                        const newQty = item.quantity + delta;
                        return newQty > 0 ? { ...item, quantity: newQty } : null;
                    }
                    return item;
                })
                .filter(Boolean) as CartItem[]
        );
    };

    const removeCartItem = (product_id: number, size: string) => {
        setCart(prev => prev.filter(item => !(item.product_id === product_id && item.size === size)));
    };

    const resetSaleForm = () => {
        setSelectedCustomerVal('Walk-in Customer|01700000000|Store Counter, Dhaka');
        setCustomerName('Walk-in Customer');
        setCustomerPhone('01700000000');
        setCustomerAddress('Store Counter, Dhaka');
        setCart([]);
        setOrderNotes('');
        setShippingCost(60);
        setDiscount(0);
    };

    const handleCompleteSale = async (e: React.FormEvent) => {
        e.preventDefault();
        if (cart.length === 0) {
            alert('Please add at least one product to the sale cart before completing.');
            return;
        }

        setSubmitting(true);
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const payload = {
                customer_name: customerName,
                customer_phone: customerPhone,
                shipping_address: customerAddress,
                payment_method: paymentMethod,
                courier_name: courierPartner,
                shipping_cost: shippingCost,
                discount: discount,
                items: cart.map(item => ({
                    product_id: item.product_id,
                    size: item.size,
                    quantity: item.quantity,
                })),
            };

            const res = await fetch('/admin/api/sales', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            if (res.ok) {
                router.visit('/admin/orders', {
                    data: { flash_message: 'Sale completed & order created successfully!' },
                });
            } else {
                const errData = await res.json();
                alert(errData.message || 'Failed to complete sale order.');
            }
        } catch (err) {
            alert('Error submitting sale order.');
        } finally {
            setSubmitting(false);
        }
    };

    const handlePrintInvoice = () => {
        window.print();
    };

    return (
        <AdminLayout activePage="create_order">
            <Head title="Create Sale / POS Terminal — Admin" />

            {/* TOP POS HEADER BANNER */}
            <div className="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div className="flex items-center gap-3.5">
                    <div className="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white flex items-center justify-center shadow-md shadow-emerald-600/20">
                        <ShoppingCart className="w-5 h-5" />
                    </div>
                    <div>
                        <div className="flex items-center gap-2">
                            <span className="text-[10px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                POINT OF SALE TERMINAL
                            </span>
                            <span className="text-[10px] text-slate-500 font-mono">LIVE COUNTER DISPATCH</span>
                        </div>
                        <h2 className="text-[18px] font-serif font-bold text-[#0f172a] uppercase">
                            Create New Sale / POS Order
                        </h2>
                    </div>
                </div>

                <div className="flex items-center gap-2">
                    <button
                        type="button"
                        onClick={resetSaleForm}
                        className="px-3.5 py-2 text-[12px] font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-1.5 cursor-pointer shadow-2xs"
                    >
                        <RotateCcw className="w-3.5 h-3.5" /> Reset Form
                    </button>
                    <Link
                        href="/admin/orders"
                        className="px-3.5 py-2 text-[12px] font-bold text-[#0284c7] bg-sky-50 border border-sky-200 rounded-xl hover:bg-sky-100 transition-colors flex items-center gap-1.5 shadow-2xs"
                    >
                        <List className="w-3.5 h-3.5" /> All Sales / Orders
                    </Link>
                </div>
            </div>

            {/* MAIN 2-COLUMN POS GRID */}
            <form onSubmit={handleCompleteSale} className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {/* LEFT COLUMN: Customer & Cart Builder (7 COLS) */}
                <div className="lg:col-span-7 space-y-6">
                    {/* 1. CUSTOMER INFORMATION */}
                    <div className="bg-white/90 border border-slate-200 p-6 rounded-2xl shadow-xs space-y-4">
                        <div className="flex justify-between items-center border-b border-slate-100 pb-3">
                            <h3 className="text-[13px] font-bold text-[#0f172a] uppercase flex items-center gap-2">
                                <User className="w-4 h-4 text-emerald-600" /> Step 1: Customer Information
                            </h3>
                            <button
                                type="button"
                                onClick={() => setShowQuickCustomerModal(true)}
                                className="bg-[#e0f2fe] hover:bg-[#bae6fd] text-[#0284c7] px-3 py-1 rounded-lg text-[11px] font-bold uppercase transition-colors flex items-center gap-1 cursor-pointer"
                            >
                                <UserPlus className="w-3.5 h-3.5" /> + Quick Add
                            </button>
                        </div>

                        <div className="space-y-3">
                            <div>
                                <label className="text-[11px] font-bold uppercase text-slate-600 block mb-1">
                                    Select Registered Customer or Walk-in
                                </label>
                                <select
                                    value={selectedCustomerVal}
                                    onChange={e => handleCustomerChange(e.target.value)}
                                    className="w-full border border-slate-300 p-2.5 rounded-xl text-[13px] font-medium focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none bg-white text-slate-800"
                                >
                                    <option value="Walk-in Customer|01700000000|Store Counter, Dhaka">
                                        🏷️ Walk-in Customer (Store Counter)
                                    </option>
                                    {customers.map(cust => (
                                        <option key={cust.id} value={`${cust.name}|${cust.phone}|${cust.address}`}>
                                            {cust.name} ({cust.phone}) - {cust.address}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                                <div>
                                    <label className="text-[11px] font-bold uppercase text-slate-500 block mb-1">
                                        Customer Phone Number
                                    </label>
                                    <input
                                        type="text"
                                        value={customerPhone}
                                        onChange={e => setCustomerPhone(e.target.value)}
                                        placeholder="017xxxxxxxx"
                                        className="w-full border border-slate-300 px-3 py-2 rounded-xl text-[12px] font-mono font-medium focus:border-emerald-600 outline-none bg-slate-50 text-slate-800"
                                    />
                                </div>
                                <div>
                                    <label className="text-[11px] font-bold uppercase text-slate-500 block mb-1">
                                        Delivery / Counter Address
                                    </label>
                                    <input
                                        type="text"
                                        value={customerAddress}
                                        onChange={e => setCustomerAddress(e.target.value)}
                                        placeholder="Street, Area, City"
                                        className="w-full border border-slate-300 px-3 py-2 rounded-xl text-[12px] font-medium focus:border-emerald-600 outline-none bg-slate-50 text-slate-800"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* 2. PRODUCT CATALOG SELECTION & CART BUILDER */}
                    <div className="bg-white/90 border border-slate-200 p-6 rounded-2xl shadow-xs space-y-4">
                        <div className="flex justify-between items-center border-b border-slate-100 pb-3">
                            <h3 className="text-[13px] font-bold text-[#0f172a] uppercase flex items-center gap-2">
                                <PackagePlus className="w-4 h-4 text-emerald-600" /> Step 2: Add Products to Sale Cart
                            </h3>
                            <span className="text-[11px] font-bold text-slate-500 font-mono">
                                {cart.length} Items Added
                            </span>
                        </div>

                        {/* SELECTOR DROPDOWN */}
                        <div className="relative">
                            <input
                                type="text"
                                placeholder="-- Search Product / Variant --"
                                value={searchQuery}
                                onChange={e => {
                                    setSearchQuery(e.target.value);
                                    setIsDropdownOpen(true);
                                }}
                                onFocus={() => setIsDropdownOpen(true)}
                                className="w-full border border-slate-300 p-2.5 rounded-xl text-[13px] font-medium focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none bg-white text-slate-800"
                            />
                            {isDropdownOpen && (
                                <div className="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                    {products.filter(p => p.name.toLowerCase().includes(searchQuery.toLowerCase()) || p.variants?.some(v => v.name.toLowerCase().includes(searchQuery.toLowerCase()))).length > 0 ? (
                                        products.filter(p => p.name.toLowerCase().includes(searchQuery.toLowerCase()) || p.variants?.some(v => v.name.toLowerCase().includes(searchQuery.toLowerCase()))).map(p => (
                                            <div key={p.id}>
                                                <div 
                                                    className="px-4 py-2 bg-slate-50 text-[12px] font-bold text-slate-700 cursor-pointer hover:bg-emerald-50 hover:text-emerald-700"
                                                    onClick={() => handleAddProductToCart(p.id, 'Base', Number(p.price))}
                                                >
                                                    {p.name} (Base) - ৳{p.price}
                                                </div>
                                                {p.variants?.filter(v => v.name.toLowerCase().includes(searchQuery.toLowerCase()) || p.name.toLowerCase().includes(searchQuery.toLowerCase())).map(v => (
                                                    <div 
                                                        key={v.id} 
                                                        className="px-4 py-2 pl-8 text-[12px] text-slate-600 cursor-pointer hover:bg-emerald-50 hover:text-emerald-700 border-t border-slate-100"
                                                        onClick={() => handleAddProductToCart(p.id, v.name, Number(v.price || p.price))}
                                                    >
                                                        {p.name} ({v.name}) - ৳{v.price || p.price}
                                                    </div>
                                                ))}
                                            </div>
                                        ))
                                    ) : (
                                        <div className="px-4 py-3 text-[12px] text-slate-500 text-center">No products found.</div>
                                    )}
                                </div>
                            )}
                            
                            {isDropdownOpen && (
                                <div className="fixed inset-0 z-40" onClick={() => setIsDropdownOpen(false)}></div>
                            )}
                        </div>

                        {/* CART ITEMS LIST */}
                        <div className="space-y-2 pt-2">
                            <label className="text-[11px] font-bold uppercase text-slate-500 block">
                                Sale Order Items
                            </label>
                            <div className="space-y-2 max-h-72 overflow-y-auto pr-1">
                                {cart.length > 0 ? (
                                    cart.map(item => (
                                        <div
                                            key={`${item.product_id}-${item.size}`}
                                            className="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-xl"
                                        >
                                            <div>
                                                <div className="font-bold text-[13px] text-slate-800">{item.name}</div>
                                                {item.size && item.size !== 'Base' && (
                                                    <div className="text-[11px] text-slate-500 uppercase tracking-widest">{item.size}</div>
                                                )}
                                                <div className="text-[11px] font-mono text-emerald-700 font-semibold mt-0.5">
                                                    ৳{item.price.toFixed(2)} x {item.quantity} = ৳{(item.price * item.quantity).toFixed(2)}
                                                </div>
                                            </div>

                                            <div className="flex items-center gap-3">
                                                <div className="flex items-center gap-1 bg-white border border-slate-300 rounded-lg p-1">
                                                    <button
                                                        type="button"
                                                        onClick={() => updateQuantity(item.product_id, item.size, -1)}
                                                        className="p-1 hover:bg-slate-100 rounded text-slate-600 cursor-pointer"
                                                    >
                                                        <Minus className="w-3.5 h-3.5" />
                                                    </button>
                                                    <span className="px-2 font-mono font-bold text-xs text-slate-800">
                                                        {item.quantity}
                                                    </span>
                                                    <button
                                                        type="button"
                                                        onClick={() => updateQuantity(item.product_id, item.size, 1)}
                                                        className="p-1 hover:bg-slate-100 rounded text-slate-600 cursor-pointer"
                                                    >
                                                        <Plus className="w-3.5 h-3.5" />
                                                    </button>
                                                </div>

                                                <button
                                                    type="button"
                                                    onClick={() => removeCartItem(item.product_id, item.size)}
                                                    className="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Remove item"
                                                >
                                                    <Trash2 className="w-4 h-4" />
                                                </button>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="p-6 text-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 text-slate-400 text-[12px] font-medium">
                                        <ShoppingBag className="w-8 h-8 mx-auto mb-2 text-slate-300" />
                                        No products added yet. Select a product above to add to this sale.
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* ORDER NOTES */}
                        <div className="pt-2">
                            <label className="text-[11px] font-bold uppercase text-slate-600 block mb-1">
                                Customer / Special Dispatch Notes (Optional)
                            </label>
                            <input
                                type="text"
                                value={orderNotes}
                                onChange={e => setOrderNotes(e.target.value)}
                                placeholder="e.g. Call before delivery, handle fragile perfume glass..."
                                className="w-full border border-slate-300 px-3 py-2 rounded-xl text-[12px] font-medium focus:border-emerald-600 outline-none bg-white text-slate-800"
                            />
                        </div>
                    </div>
                </div>

                {/* RIGHT COLUMN: Payment, Logistics & Billing Summary (5 COLS) */}
                <div className="lg:col-span-5 space-y-6">
                    {/* STEP 3: PAYMENT & SHIPPING */}
                    <div className="bg-white/90 border border-slate-200 p-6 rounded-2xl shadow-xs space-y-4">
                        <h3 className="text-[13px] font-bold text-[#0f172a] uppercase border-b border-slate-100 pb-3 flex items-center gap-2">
                            <CreditCard className="w-4 h-4 text-emerald-600" /> Step 3: Payment & Shipping
                        </h3>

                        {/* Payment Method */}
                        <div className="space-y-1.5">
                            <label className="text-[11px] font-bold uppercase text-slate-600 block">
                                Payment Method
                            </label>
                            <select
                                value={paymentMethod}
                                onChange={e => setPaymentMethod(e.target.value)}
                                className="w-full border border-slate-300 p-2.5 rounded-xl text-[12.5px] font-bold focus:border-emerald-600 outline-none bg-white text-slate-800"
                            >
                                <option value="Cash on Delivery">💵 Cash on Delivery (COD)</option>
                                <option value="SSLCommerz Gateway">💳 SSLCommerz (Cards/NetBanking/MFS)</option>
                                <option value="bKash Merchant">📱 bKash Merchant Checkout</option>
                                <option value="EPS Electronic Payment">🏛️ EPS (Electronic Payment Service)</option>
                                <option value="Cash at Store Counter">🏪 Cash at Store Counter / POS</option>
                            </select>
                        </div>

                        {/* Courier Partner */}
                        <div className="space-y-1.5">
                            <label className="text-[11px] font-bold uppercase text-slate-600 block">
                                Courier / Dispatch Partner
                            </label>
                            <select
                                value={courierPartner}
                                onChange={e => setCourierPartner(e.target.value)}
                                className="w-full border border-slate-300 p-2.5 rounded-xl text-[12.5px] font-bold focus:border-emerald-600 outline-none bg-white text-slate-800"
                            >
                                <option value="Pathao Courier API">🚚 Pathao Courier API</option>
                                <option value="Steadfast Courier">📦 Steadfast Courier</option>
                                <option value="RedX Logistics">🚛 RedX Logistics</option>
                                <option value="Paperfly">📫 Paperfly Delivery</option>
                                <option value="In-Store Pickup">🛒 In-Store Walk-in Pickup</option>
                            </select>
                        </div>

                        {/* Delivery Region & Discount */}
                        <div className="grid grid-cols-2 gap-3">
                            <div>
                                <label className="text-[11px] font-bold uppercase text-slate-600 block mb-1">
                                    Delivery Region
                                </label>
                                <select
                                    value={shippingCost}
                                    onChange={e => setShippingCost(Number(e.target.value))}
                                    className="w-full border border-slate-300 p-2 rounded-xl text-[12px] font-semibold focus:border-emerald-600 outline-none bg-white text-slate-800"
                                >
                                    <option value={60}>Inside Dhaka (৳60)</option>
                                    <option value={120}>Outside Dhaka (৳120)</option>
                                    <option value={0}>Store Pickup / Free (৳0)</option>
                                </select>
                            </div>
                            <div>
                                <label className="text-[11px] font-bold uppercase text-slate-600 block mb-1">
                                    Special Discount (৳)
                                </label>
                                <input
                                    type="number"
                                    value={discount}
                                    min="0"
                                    onChange={e => setDiscount(Number(e.target.value))}
                                    placeholder="0"
                                    className="w-full border border-slate-300 px-3 py-2 rounded-xl text-[12px] font-mono font-bold focus:border-emerald-600 outline-none bg-white text-slate-800"
                                />
                            </div>
                        </div>
                    </div>

                    {/* DARK RECEIPT SUMMARY CARD */}
                    <div className="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 rounded-2xl shadow-xl space-y-4 border border-slate-700">
                        <div className="flex justify-between items-center border-b border-slate-700 pb-3">
                            <span className="text-[12px] font-bold uppercase tracking-wider text-emerald-400 flex items-center gap-1.5">
                                <Receipt className="w-4 h-4" /> Sale Bill Summary
                            </span>
                            <span className="text-[11px] text-slate-400 font-mono">
                                {new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}
                            </span>
                        </div>

                        <div className="space-y-2.5 text-[13px]">
                            <div className="flex justify-between text-slate-300">
                                <span>Cart Subtotal:</span>
                                <span className="font-mono font-bold text-white">৳{cartSubtotal.toFixed(2)} BDT</span>
                            </div>
                            <div className="flex justify-between text-slate-300">
                                <span>Shipping / Delivery:</span>
                                <span className="font-mono font-bold text-emerald-300">+ ৳{shippingCost.toFixed(2)} BDT</span>
                            </div>
                            <div className="flex justify-between text-slate-300">
                                <span>Discount:</span>
                                <span className="font-mono font-bold text-rose-300">- ৳{discount.toFixed(2)} BDT</span>
                            </div>
                            <div className="pt-3 border-t border-slate-700 flex justify-between items-baseline">
                                <span className="text-[13px] font-bold uppercase tracking-wider text-slate-200">
                                    TOTAL PAYABLE:
                                </span>
                                <span className="text-[26px] font-serif font-bold text-emerald-400 font-mono">
                                    ৳{totalPayable.toFixed(2)} BDT
                                </span>
                            </div>
                        </div>

                        <div className="pt-2 space-y-2">
                            <button
                                type="submit"
                                disabled={submitting || cart.length === 0}
                                className="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 py-3.5 rounded-xl font-bold uppercase tracking-wider text-[13px] shadow-lg shadow-emerald-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98 disabled:opacity-50"
                            >
                                <CheckCircle className="w-4 h-4" />{' '}
                                {submitting ? 'Processing Sale...' : 'Complete Sale & Generate Order'}
                            </button>
                            <button
                                type="button"
                                onClick={handlePrintInvoice}
                                className="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-colors flex items-center justify-center gap-1.5 cursor-pointer border border-slate-700"
                            >
                                <Printer className="w-3.5 h-3.5" /> Print POS Receipt Draft
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {/* QUICK ADD CUSTOMER MODAL */}
            {showQuickCustomerModal && (
                <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
                    <div className="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
                        <div className="flex items-center justify-between border-b pb-3">
                            <h3 className="text-sm font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                                <UserPlus className="w-4 h-4 text-emerald-600" /> Quick Add Customer
                            </h3>
                            <button
                                type="button"
                                onClick={() => setShowQuickCustomerModal(false)}
                                className="text-slate-400 hover:text-slate-600"
                            >
                                <X className="w-4 h-4" />
                            </button>
                        </div>

                        <form onSubmit={handleQuickAddCustomerSubmit} className="space-y-3">
                            <div>
                                <label className="text-[11px] font-bold uppercase text-slate-600 block mb-1">
                                    Customer Name *
                                </label>
                                <input
                                    type="text"
                                    value={newCustName}
                                    onChange={e => setNewCustName(e.target.value)}
                                    required
                                    placeholder="Full Name"
                                    className="w-full border border-slate-300 px-3 py-2 rounded-xl text-xs outline-none focus:border-emerald-600"
                                />
                            </div>

                            <div>
                                <label className="text-[11px] font-bold uppercase text-slate-600 block mb-1">
                                    Phone Number
                                </label>
                                <input
                                    type="text"
                                    value={newCustPhone}
                                    onChange={e => setNewCustPhone(e.target.value)}
                                    placeholder="017xxxxxxxx"
                                    className="w-full border border-slate-300 px-3 py-2 rounded-xl text-xs outline-none focus:border-emerald-600"
                                />
                            </div>

                            <div>
                                <label className="text-[11px] font-bold uppercase text-slate-600 block mb-1">
                                    Address
                                </label>
                                <input
                                    type="text"
                                    value={newCustAddress}
                                    onChange={e => setNewCustAddress(e.target.value)}
                                    placeholder="Address, City"
                                    className="w-full border border-slate-300 px-3 py-2 rounded-xl text-xs outline-none focus:border-emerald-600"
                                />
                            </div>

                            <div className="pt-2 flex justify-end gap-2">
                                <button
                                    type="button"
                                    onClick={() => setShowQuickCustomerModal(false)}
                                    className="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    className="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold"
                                >
                                    Save Customer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
