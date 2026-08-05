'use client';

import React, { useState, useEffect, useRef } from 'react';

interface ScentTrailProps {
  children: React.ReactNode;
  className?: string;
}

export default function ScentTrail({ children, className = '' }: ScentTrailProps) {
  const containerRef = useRef<HTMLDivElement>(null);
  const [pos, setPos] = useState({ x: -100, y: -100 });
  const [targetPos, setTargetPos] = useState({ x: -100, y: -100 });
  const [isHovered, setIsHovered] = useState(false);

  // Smooth 150ms lag animation loop
  useEffect(() => {
    if (!isHovered) return;
    let animationFrameId: number;

    const lerp = (start: number, end: number, factor: number) => start + (end - start) * factor;

    const animate = () => {
      setPos(prev => ({
        x: lerp(prev.x, targetPos.x, 0.15),
        y: lerp(prev.y, targetPos.y, 0.15),
      }));
      animationFrameId = requestAnimationFrame(animate);
    };

    animationFrameId = requestAnimationFrame(animate);
    return () => cancelAnimationFrame(animationFrameId);
  }, [isHovered, targetPos]);

  const handleMouseMove = (e: React.MouseEvent<HTMLDivElement>) => {
    if (!containerRef.current) return;
    const rect = containerRef.current.getBoundingClientRect();
    setTargetPos({
      x: e.clientX - rect.left,
      y: e.clientY - rect.top,
    });
  };

  return (
    <div
      ref={containerRef}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
      onMouseMove={handleMouseMove}
      className={`relative overflow-hidden ${className}`}
    >
      {/* Diffusing Amber Radial Blur Trail (Behind imagery) */}
      {isHovered && (
        <div
          className="pointer-events-none absolute transition-opacity duration-500 rounded-full blur-2xl z-0"
          style={{
            left: `${pos.x - 75}px`,
            top: `${pos.y - 75}px`,
            width: '150px',
            height: '150px',
            background: 'radial-gradient(circle, rgba(184, 113, 46, 0.35) 0%, rgba(184, 113, 46, 0.12) 50%, rgba(184, 113, 46, 0) 100%)',
            mixBlendMode: 'multiply',
          }}
        />
      )}
      <div className="relative z-10">{children}</div>
    </div>
  );
}
