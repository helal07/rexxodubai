'use client';

import React from 'react';
import { usePage } from '@inertiajs/react';
import { Mail, MessageSquare, Phone, MapPin, Clock } from 'lucide-react';

export default function ContactPage() {
  const { siteSettings, apiSettings }: any = usePage().props;
  const settings = siteSettings || apiSettings || {};

  // Resolve fallbacks
  const whatsappNumber = (settings.whatsapp || settings.contact_phone || '8801700000000').replace(/\D/g, '');
  const contactEmail = settings.contact_email || settings.email || 'client.service.bd@rexxobd.com';
  const displayPhone = settings.contact_phone || settings.phone || '+880 1700 000 000';
  const displayAddress = settings.contact_address || settings.address || 'Raaxo Dubai Perfume House, Doreen Vinciat Shopping Complex, Rupnagar R/A, Mirpur-5, Dhaka-1216, Bangladesh';
  return (
    <div className="max-w-[1440px] mx-auto px-6 pt-28 pb-16 space-y-16 animate-fade-in">
      {/* Header */}
      <div className="border-b border-[#DEDBD4] pb-6">
        <span className="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#B8712E] block mb-1">
          CLIENT SERVICE · CONCIERGE
        </span>
        <h1 className="font-serif text-[36px] md:text-[48px] text-[#0A0A0A] uppercase font-bold tracking-tight">
          CONTACT US
        </h1>
        <p className="text-[14px] text-[#6E6B66] max-w-xl mt-2 font-light">
          Our fragrance concierges are available to assist with perfume inquiries, custom formulations, and bespoke consultations.
        </p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
        {/* Left: Contact Options */}
        <div className="lg:col-span-6 space-y-6">
          <h2 className="text-[18px] font-sans font-bold text-[#0A0A0A] uppercase tracking-wide">
            Direct Messaging Channels
          </h2>

          <div className="space-y-4">
            {/* WhatsApp */}
            <a
              href={`https://api.whatsapp.com/send?phone=${whatsappNumber}&text=Hello%20ReXxo%20Bd,%20I%20would%20like%20to%20inquire%20about%20your%20luxury%20perfumes.`}
              target="_blank"
              rel="noreferrer"
              className="flex justify-between items-center p-6 border border-[#DEDBD4] hover:border-black transition-all bg-white group cursor-pointer"
            >
              <div>
                <span className="text-[16px] font-medium text-[#0A0A0A] group-hover:font-semibold block">
                  WhatsApp Direct Message
                </span>
                <span className="text-[12px] text-[#6E6B66]">Instant chat with our Client Service team</span>
              </div>
              <MessageSquare size={22} className="text-[#0A0A0A]" />
            </a>

            {/* Gmail Web */}
            <a
              href={`https://mail.google.com/mail/?view=cm&fs=1&to=${contactEmail}&su=Inquiry%20-%20ReXxo%20Bd%20Perfumes&body=Hello%20ReXxo%20Bd%20Client%20Service,%20I%20would%20like%20to%20inquire%20about%20your%20luxury%20perfumes.`}
              target="_blank"
              rel="noreferrer"
              className="flex justify-between items-center p-6 border border-[#DEDBD4] hover:border-black transition-all bg-white group cursor-pointer"
            >
              <div>
                <span className="text-[16px] font-medium text-[#0A0A0A] group-hover:font-semibold block">
                  Send Message via Gmail
                </span>
                <span className="text-[12px] text-[#6E6B66]">{contactEmail}</span>
              </div>
              <Mail size={22} className="text-[#0A0A0A]" />
            </a>

            {/* Default Email */}
            <a
              href={`mailto:${contactEmail}?subject=Inquiry%20-%20ReXxo%20Bd%20Perfumes&body=Hello%20ReXxo%20Bd%20Client%20Service,`}
              className="flex justify-between items-center p-6 border border-[#DEDBD4] hover:border-black transition-all bg-white group cursor-pointer"
            >
              <div>
                <span className="text-[16px] font-medium text-[#0A0A0A] group-hover:font-semibold block">
                  Default Mail Application
                </span>
                <span className="text-[12px] text-[#6E6B66]">Triggers Outlook or Apple Mail</span>
              </div>
              <Mail size={22} className="text-[#6E6B66]" />
            </a>
          </div>
        </div>

        {/* Right: Boutique Information */}
        <div className="lg:col-span-6 space-y-8 bg-[#F5F3EF] p-8 md:p-10 border border-[#DEDBD4]">
          <h2 className="text-[18px] font-sans font-bold text-[#0A0A0A] uppercase tracking-wide">
            Concierge Information & Hours
          </h2>

          <div className="space-y-6 text-[14px] text-[#0A0A0A]">
            <div className="flex items-start gap-4">
              <Clock size={20} className="text-[#B8712E] shrink-0 mt-0.5" />
              <div>
                <strong className="block font-semibold uppercase text-[12px] tracking-wider text-[#6E6B66]">SERVICE HOURS</strong>
                <span>Monday – Saturday: 9:00 AM – 8:00 PM</span>
                <span className="block">Sunday: 9:00 AM – 6:00 PM</span>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <Phone size={20} className="text-[#B8712E] shrink-0 mt-0.5" />
              <div>
                <strong className="block font-semibold uppercase text-[12px] tracking-wider text-[#6E6B66]">TELEPHONE INQUIRIES</strong>
                <span>{displayPhone}</span>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <MapPin size={20} className="text-[#B8712E] shrink-0 mt-0.5" />
              <div>
                <strong className="block font-semibold uppercase text-[12px] tracking-wider text-[#6E6B66]">FLAGSHIP BOUTIQUE</strong>
                <span>{displayAddress}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
