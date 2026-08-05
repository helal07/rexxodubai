import React from 'react';


export default function AboutPage() {
  return (
    <div className="max-w-[1440px] mx-auto px-6 pt-28 pb-12 space-y-24">
      {/* Brand Hero */}
      <section className="text-center max-w-3xl mx-auto space-y-6">
        <span className="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#B8712E] block">
          THE HOUSE OF REXXO BD
        </span>
        <h1 className="font-serif text-[42px] md:text-[56px] text-[#0A0A0A] font-light leading-tight">
          Quiet Confidence in Scent & Form
        </h1>
        <p className="text-[15px] text-[#6E6B66] font-light leading-relaxed">
          ReXxo Bd was founded on a simple thesis: fine fragrance is sculpture for the skin. We do not design for transient fashion cycles, but for sensory longevity.
        </p>
      </section>

      {/* Editorial Image & Craft Split */}
      <section className="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div className="aspect-[4/5] relative bg-[#F5F3EF] border border-[#DEDBD4] overflow-hidden">
          <img
            src="https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=1200&q=85"
            alt="Handcrafting Extraits"


            className="object-cover"
          />
        </div>
        <div className="space-y-6">
          <span className="text-[11px] uppercase tracking-[0.14em] font-semibold text-[#B8712E]">
            01 · RAW INGREDIENT RIGOR
          </span>
          <h2 className="font-serif text-[32px] text-[#0A0A0A] font-light">
            Distilled Rose & Resin
          </h2>
          <p className="text-[14px] text-[#6E6B66] leading-relaxed font-light">
            Our Damask roses are harvested in Grasse at first light before the sun evaporates the delicate volatile terpenes. They are combined with rare amber absolute from Laotian resins and aged in dark glass carboys for six months.
          </p>
          <div className="border-t border-[#DEDBD4] pt-4 grid grid-cols-2 gap-4 text-[12px] uppercase tracking-wider text-[#0A0A0A]">
            <div>
              <strong className="block text-[#B8712E] text-[18px]">100%</strong> Hand-Harvested Petals
            </div>
            <div>
              <strong className="block text-[#B8712E] text-[18px]">6 Months</strong> Maceration Period
            </div>
          </div>
        </div>
      </section>

      {/* Glass Sculpture Split */}
      <section className="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div className="space-y-6 md:order-1">
          <span className="text-[11px] uppercase tracking-[0.14em] font-semibold text-[#B8712E]">
            02 · SCULPTURAL ARCHITECTURE
          </span>
          <h2 className="font-serif text-[32px] text-[#0A0A0A] font-light">
            The Amber Glass Vessel
          </h2>
          <p className="text-[14px] text-[#6E6B66] leading-relaxed font-light">
            Each bottle is weighted, crisp, and hard-edged. Borrowing architectural restraint, our bottles feature zero unnecessary ornament—only hand-stamped typographic labels and a heavy blackened steel cap.
          </p>
        </div>
        <div className="aspect-[4/5] relative bg-[#F5F3EF] border border-[#DEDBD4] overflow-hidden md:order-2">
          <img
            src="https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=1200&q=85"
            alt="Sculptural Glass Bottle"

            className="object-cover"
          />
        </div>
      </section>
    </div>
  );
}
