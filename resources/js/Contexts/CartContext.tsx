'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';

export interface CartItem {
  id: number;
  name: string;
  slug: string;
  price: number;
  size: string;
  image: string;
  concentration: string;
  quantity: number;
}

interface CartContextType {
  items: CartItem[];
  isOpen: boolean;
  openCart: () => void;
  closeCart: () => void;
  addItem: (item: Omit<CartItem, 'quantity'>, qty?: number, openDrawer?: boolean) => void;
  removeItem: (id: number, size: string) => void;
  updateQuantity: (id: number, size: string, qty: number) => void;
  clearCart: () => void;
  totalCount: number;
  subtotal: number;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

export function CartProvider({ children }: { children: React.ReactNode }) {
  const [items, setItems] = useState<CartItem[]>([]);
  const [isOpen, setIsOpen] = useState(false);
  const [mounted, setMounted] = useState(false);

  // Load cart from LocalStorage on mount safely
  useEffect(() => {
    setMounted(true);
    try {
      const saved = localStorage.getItem('rexxo_cart');
      if (saved) {
        setItems(JSON.parse(saved));
      }
    } catch (e) {
      console.error(e);
    }
  }, []);

  // Save cart to LocalStorage
  useEffect(() => {
    if (!mounted) return;
    try {
      localStorage.setItem('rexxo_cart', JSON.stringify(items));
    } catch (e) {
      console.error(e);
    }
  }, [items, mounted]);

  const openCart = () => setIsOpen(true);
  const closeCart = () => setIsOpen(false);

  const addItem = (newItem: Omit<CartItem, 'quantity'>, qty = 1, openDrawer = true) => {
    const validQty = Math.max(1, qty);
    setItems(prev => {
      const existingIndex = prev.findIndex(i => i.id === newItem.id && i.size === newItem.size);
      if (existingIndex > -1) {
        const updated = [...prev];
        updated[existingIndex].quantity += validQty;
        return updated;
      }
      return [...prev, { ...newItem, quantity: validQty }];
    });
    if (openDrawer) {
      setIsOpen(true);
    }
  };

  const removeItem = (id: number, size: string) => {
    setItems(prev => prev.filter(i => !(i.id === id && i.size === size)));
  };

  const updateQuantity = (id: number, size: string, qty: number) => {
    if (qty <= 0) {
      removeItem(id, size);
      return;
    }
    setItems(prev =>
      prev.map(item => (item.id === id && item.size === size ? { ...item, quantity: qty } : item))
    );
  };

  const clearCart = () => setItems([]);

  const totalCount = items.reduce((acc, item) => acc + item.quantity, 0);
  const subtotal = items.reduce((acc, item) => acc + item.price * item.quantity, 0);

  return (
    <CartContext.Provider
      value={{
        items,
        isOpen,
        openCart,
        closeCart,
        addItem,
        removeItem,
        updateQuantity,
        clearCart,
        totalCount: mounted ? totalCount : 0,
        subtotal: mounted ? subtotal : 0,
      }}
    >
      {children}
    </CartContext.Provider>
  );
}

export function useCart() {
  const context = useContext(CartContext);
  if (!context) throw new Error('useCart must be used within CartProvider');
  return context;
}
