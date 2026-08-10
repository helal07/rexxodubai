'use client';

import React, { useRef, useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function HeroVideo() {
  const { siteSettings, apiSettings, cmsData }: any = usePage().props;
  const oldSettings = siteSettings || apiSettings || {};
  const settings = { ...oldSettings, ...(cmsData?.home_hero || {}) };
  const videoRef = useRef<HTMLVideoElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [isPlaying, setIsPlaying] = useState(true);
  const [videoError, setVideoError] = useState(false);

  const videoUrl = settings.hero_video_url || settings.hero_video || 'https://assets.mixkit.co/videos/preview/mixkit-perfume-bottle-in-a-dark-environment-42525-large.mp4';
  const posterUrl = settings.hero_poster_url || 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=2400&q=90';
  const heroSubtitle = settings.hero_subtitle || 'NEW COLLECTION';
  const heroTitle = settings.hero_title || 'Fall Winter 2026';
  const link1Text = settings.hero_link_1_text || 'FOR HER';
  const link1Url = settings.hero_link_1_url || '/perfumes?gender=women';
  const link2Text = settings.hero_link_2_text || 'FOR HIM';
  const link2Url = settings.hero_link_2_url || '/perfumes?gender=men';

  // Reload video whenever the video URL in settings changes
  useEffect(() => {
    setVideoError(false);
    if (videoRef.current) {
      videoRef.current.load();
      videoRef.current.muted = true;
      videoRef.current.play().catch(() => {
        // Fallback to canvas if video fails
      });
    }
  }, [videoUrl]);

  // AI Particle Amber Vapor Animation Canvas (Guaranteed playback fallback)
  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let animationFrameId: number;
    let width = (canvas.width = window.innerWidth);
    let height = (canvas.height = window.innerHeight);

    const handleResize = () => {
      if (!canvas) return;
      width = canvas.width = window.innerWidth;
      height = canvas.height = window.innerHeight;
    };
    window.addEventListener('resize', handleResize);

    // AI Vapor particles
    const particles = Array.from({ length: 45 }, () => ({
      x: Math.random() * width,
      y: Math.random() * height,
      radius: Math.random() * 120 + 40,
      vx: (Math.random() - 0.5) * 0.4,
      vy: (Math.random() - 0.5) * 0.4,
      alpha: Math.random() * 0.35 + 0.1,
    }));

    const render = () => {
      ctx.fillStyle = '#0A0A0A';
      ctx.fillRect(0, 0, width, height);

      particles.forEach(p => {
        p.x += p.vx;
        p.y += p.vy;

        if (p.x < -p.radius) p.x = width + p.radius;
        if (p.x > width + p.radius) p.x = -p.radius;
        if (p.y < -p.radius) p.y = height + p.radius;
        if (p.y > height + p.radius) p.y = -p.radius;

        const gradient = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.radius);
        gradient.addColorStop(0, `rgba(184, 113, 46, ${p.alpha})`);
        gradient.addColorStop(0.6, `rgba(184, 113, 46, ${p.alpha * 0.4})`);
        gradient.addColorStop(1, 'rgba(10, 10, 10, 0)');

        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
        ctx.fill();
      });

      if (isPlaying) {
        animationFrameId = requestAnimationFrame(render);
      }
    };

    render();

    return () => {
      cancelAnimationFrame(animationFrameId);
      window.removeEventListener('resize', handleResize);
    };
  }, [isPlaying]);

  const togglePlay = () => {
    if (videoRef.current) {
      if (isPlaying) {
        videoRef.current.pause();
      } else {
        videoRef.current.play().catch(() => {});
      }
    }
    setIsPlaying(!isPlaying);
  };

  return (
    <section className="relative h-screen w-full bg-[#0A0A0A] overflow-hidden flex flex-col justify-end pb-16 md:pb-24">
      {/* 1. Canvas AI Animated Amber Vapor (Always active background) */}
      <canvas ref={canvasRef} className="absolute inset-0 w-full h-full object-cover z-0" />

      {/* 2. Dynamic Hero Video Background Clip from Site Settings */}
      {!videoError && videoUrl && (
        <video
          key={videoUrl}
          ref={videoRef}
          autoPlay
          muted
          loop
          playsInline
          onError={() => setVideoError(true)}
          poster={posterUrl}
          className="absolute inset-0 w-full h-full object-cover opacity-80 object-center z-5 transition-opacity duration-700"
        >
          <source src={videoUrl} type="video/mp4" />
          <source src={videoUrl} type="video/webm" />
        </video>
      )}

      {/* Dark Vignette Gradient Overlay */}
      <div className="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-black/40 z-10 pointer-events-none" />

      {/* Centered Prada-Style Hero Content */}
      <div className="relative z-20 text-center text-white px-6 max-w-4xl mx-auto space-y-4 animate-fade-in">
        {heroSubtitle && (
          <span className="text-[11px] uppercase tracking-[0.2em] font-medium text-white/90 block">
            {heroSubtitle}
          </span>
        )}
        {heroTitle && (
          <h2 className="text-[36px] sm:text-[54px] md:text-[64px] font-sans font-bold tracking-tight text-white leading-tight">
            {heroTitle}
          </h2>
        )}

        {/* Dual Underlined Action Links ("FOR HER" / "FOR HIM") */}
        {(link1Text || link2Text) && (
          <div className="pt-3 flex items-center justify-center space-x-12">
            {link1Text && (
              <Link
                href={link1Url}
                className="text-[13px] uppercase font-bold tracking-[0.14em] text-white hover:text-[#B8712E] transition-colors border-b-2 border-white pb-0.5"
              >
                {link1Text}
              </Link>
            )}
            {link2Text && (
              <Link
                href={link2Url}
                className="text-[13px] uppercase font-bold tracking-[0.14em] text-white hover:text-[#B8712E] transition-colors border-b-2 border-white pb-0.5"
              >
                {link2Text}
              </Link>
            )}
          </div>
        )}
      </div>

      {/* Bottom Right Interactive Control Icon (Prada || Button) */}
      <button
        onClick={togglePlay}
        className="absolute bottom-8 right-8 z-20 text-white/80 hover:text-white transition-opacity p-2 cursor-pointer focus:outline-none"
        aria-label={isPlaying ? 'Pause campaign video' : 'Play campaign video'}
      >
        <span className="font-mono text-[15px] tracking-widest uppercase font-bold select-none">
          {isPlaying ? '||' : '▶'}
        </span>
      </button>
    </section>
  );
}
