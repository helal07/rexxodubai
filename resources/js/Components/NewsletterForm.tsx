'use client';

import React, { useState } from 'react';

export default function NewsletterForm() {
  const [email, setEmail] = useState('');
  const [subscribed, setSubscribed] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (email) {
      setSubscribed(true);
    }
  };

  if (subscribed) {
    return (
      <div className="pt-4 text-[13px] text-[#B8712E] font-semibold tracking-wider uppercase animate-fade-in">
        ✓ Thank you for subscribing to ReXxo Bd Private Announcements.
      </div>
    );
  }

  return (
    <form className="flex border-b border-[#0A0A0A] pt-4 pb-2" onSubmit={handleSubmit}>
      <input
        type="email"
        required
        value={email}
        onChange={e => setEmail(e.target.value)}
        placeholder="Enter your email address..."
        className="w-full bg-transparent text-[13px] text-[#0A0A0A] focus:outline-none placeholder:text-[#6E6B66]"
      />
      <button
        type="submit"
        className="text-[11px] font-bold uppercase tracking-widest text-[#B8712E] whitespace-nowrap pl-4 cursor-pointer"
      >
        SUBSCRIBE →
      </button>
    </form>
  );
}
