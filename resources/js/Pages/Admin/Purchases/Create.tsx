import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    ShoppingBag,
    Plus,
    CheckSquare,
    ArrowLeft,
    Briefcase,
    Boxes,
    Calendar,
    CreditCard
} from 'lucide-react';

interface Supplier {
    id: number;
    company_name: string;
}

interface Product {
    id: number;
    name: string;
    price: number;
    stock: number;
}

interface CreatePurchaseProps {
    suppliers: Supplier[];
    products: Product[];
}

export default function CreatePurchase({ suppliers = [], products = [] }: CreatePurchaseProps) {
    const todayStr = new Date().toISOString().split('T')[0];
    const defaultRef = `PO-${new Date().getFullYear()}-${Math.floor(100 + Math.random() * 900)}`;

    const [supplierId, setSupplierId] = useState<string>('');
    const [referenceNo, setReferenceNo] = useState<string>(defaultRef);
    const [purchaseDate, setPurchaseDate] = useState<string>(todayStr);
    const [paymentStatus, setPaymentStatus] = useState<string>('paid');

    const [selectedProductId, setSelectedProductId] = useState<string>('');
    const [quantity, setQuantity] = useState<number | string>('');
    const [unitCost, setUnitCost] = useState<number | string>('');

    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!supplierId) {
            alert('Please select a supplier.');
            return;
        }
        if (!selectedProductId || !quantity || !unitCost) {
            alert('Please fill out the product, quantity, and unit cost fields.');
            return;
        }

        setSubmitting(true);
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const payload = {
                supplier_id: Number(supplierId),
                reference_no: referenceNo,
                purchase_date: purchaseDate,
                status: 'Received',
                payment_status: paymentStatus,
                items: [
                    {
                        product_id: Number(selectedProductId),
                        quantity: Number(quantity),
                        unit_cost: Number(unitCost),
                    },
                ],
            };

            const res = await fetch('/admin/api/purchases', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            if (res.ok) {
                router.visit('/admin/purchases/list', {
                    data: { flash_message: 'Purchase Order created successfully!' },
                });
            } else {
                const errData = await res.json();
                alert(errData.message || 'Failed to save purchase order. Ensure reference number is unique.');
            }
        } catch (err) {
            alert('Network error while saving purchase order.');
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <AdminLayout
            activePage="purchase_add"
            pageTitle="Add New Purchase Order"
            pageSubtitle="Record a new inventory purchase order from your suppliers to restock product stock."
            headerActions={
                <Link
                    href="/admin/purchases/list"
                    className="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-1.5"
                >
                    <ArrowLeft className="w-4 h-4" /> Purchase History
                </Link>
            }
        >
            <Head title="Add Purchase — Admin" />

            <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                    <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                        <ShoppingBag className="w-5 h-5" />
                    </div>
                    <div>
                        <h2 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Purchase Order Details
                        </h2>
                        <p className="text-[11px] text-[#64748b]">Select supplier, item quantity, cost, and payment status.</p>
                    </div>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    {/* ROW 1: SUPPLIER & REFERENCE */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">
                                Supplier / Vendor *
                            </label>
                            <select
                                value={supplierId}
                                onChange={e => setSupplierId(e.target.value)}
                                required
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold bg-white text-[#0f172a] outline-none focus:border-[#0284c7]"
                            >
                                <option value="">Select Supplier...</option>
                                {suppliers.map(sup => (
                                    <option key={sup.id} value={sup.id}>
                                        {sup.company_name}
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div>
                            <label className="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">
                                Purchase Reference No. *
                            </label>
                            <input
                                type="text"
                                value={referenceNo}
                                onChange={e => setReferenceNo(e.target.value)}
                                required
                                placeholder="e.g. PO-2026-089"
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold font-mono bg-white text-[#0f172a] outline-none focus:border-[#0284c7]"
                            />
                        </div>
                    </div>

                    {/* ROW 2: ITEM / QUANTITY / UNIT COST */}
                    <div className="border border-[#e2e8f0] bg-[#f8fafc] rounded-2xl p-5 space-y-4">
                        <span className="text-[11px] font-bold uppercase tracking-wider text-[#64748b] block">
                            Inventory Material & Quantities
                        </span>
                        <div className="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div className="md:col-span-6">
                                <label className="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">
                                    Item / Material *
                                </label>
                                <select
                                    value={selectedProductId}
                                    onChange={e => setSelectedProductId(e.target.value)}
                                    required
                                    className="w-full border border-[#cbd5e1] px-3.5 py-2.5 rounded-xl text-[13px] font-bold bg-white text-[#0f172a] outline-none focus:border-[#0284c7]"
                                >
                                    <option value="">Select Product...</option>
                                    {products.map(prod => (
                                        <option key={prod.id} value={prod.id}>
                                            {prod.name} (Current Stock: {prod.stock})
                                        </option>
                                    ))}
                                </select>
                            </div>

                            <div className="md:col-span-3">
                                <label className="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">
                                    Quantity *
                                </label>
                                <input
                                    type="number"
                                    value={quantity}
                                    onChange={e => setQuantity(e.target.value)}
                                    required
                                    placeholder="0"
                                    className="w-full border border-[#cbd5e1] px-3.5 py-2.5 rounded-xl text-[13px] font-bold font-mono bg-white text-[#0f172a] outline-none focus:border-[#0284c7]"
                                />
                            </div>

                            <div className="md:col-span-3">
                                <label className="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">
                                    Unit Cost (৳) *
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    value={unitCost}
                                    onChange={e => setUnitCost(e.target.value)}
                                    required
                                    placeholder="0.00"
                                    className="w-full border border-[#cbd5e1] px-3.5 py-2.5 rounded-xl text-[13px] font-bold font-mono bg-white text-[#0f172a] outline-none focus:border-[#0284c7]"
                                />
                            </div>
                        </div>
                    </div>

                    {/* ROW 3: PAYMENT STATUS & DATE */}
                    <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between border-t border-[#e2e8f0] pt-6 gap-4">
                        <div className="flex items-center gap-6">
                            <div>
                                <label className="text-[11px] font-bold uppercase text-[#475569] block mb-1">
                                    Payment Status:
                                </label>
                                <select
                                    value={paymentStatus}
                                    onChange={e => setPaymentStatus(e.target.value)}
                                    className="border border-[#cbd5e1] px-3.5 py-2 rounded-xl text-[12px] font-bold bg-white text-[#0f172a] outline-none focus:border-[#0284c7]"
                                >
                                    <option value="paid">Paid</option>
                                    <option value="due">Due / Unpaid</option>
                                    <option value="partial">Partial</option>
                                </select>
                            </div>

                            <div>
                                <label className="text-[11px] font-bold uppercase text-[#475569] block mb-1">
                                    Purchase Date:
                                </label>
                                <input
                                    type="date"
                                    value={purchaseDate}
                                    onChange={e => setPurchaseDate(e.target.value)}
                                    className="border border-[#cbd5e1] px-3.5 py-2 rounded-xl text-[12px] font-bold bg-white text-[#0f172a] outline-none focus:border-[#0284c7]"
                                />
                            </div>
                        </div>

                        <button
                            type="submit"
                            disabled={submitting}
                            className="w-full sm:w-auto bg-[#0284c7] hover:bg-[#0369a1] text-white px-8 py-3.5 rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2 cursor-pointer transition-all"
                        >
                            <CheckSquare className="w-4 h-4" />
                            {submitting ? 'Saving...' : 'Save Purchase Order'}
                        </button>
                    </div>
                </form>
            </div>
        </AdminLayout>
    );
}
