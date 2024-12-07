import React, { useMemo } from 'react'

interface SVGLogoPlaceholderProps {
  text: string
  textSize?: 'xs' | 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl' | '5xl' | '6xl' | '7xl' | '8xl' | '9xl' | '10xl'
  width?: number
  height?: number
  shape?: 'circle' | 'squircle' | 'hexagon' | 'square'
  color?: string
  className?: string
}

export function SVGLogoPlaceholder({ 
  text,
  textSize = 'xl',
  width = 200, 
  height = 200, 
  shape = 'square',
  color,
  className
}: SVGLogoPlaceholderProps) {
  const sanitizedText = useMemo(() => text.replace(/\s+/g, '-'), [text])
  const uniqueId = useMemo(() => `shape-mask-${sanitizedText}-${Math.random().toString(36).substr(2, 9)}`, [sanitizedText])

  const generateShapes = () => {
    const shapes: JSX.Element[] = []
    const hue = Math.floor(Math.random() * 360)
    const saturation = 70 + Math.floor(Math.random() * 30)
    const lightness = 40 + Math.floor(Math.random() * 20)

    const fillColor = color || `hsl(${hue}, ${saturation}%, ${lightness}%)`

    for (let i = 0; i < sanitizedText.length; i++) {
      const charCode = sanitizedText.charCodeAt(i)
      const x = (charCode % 10) * (width / 10)
      const y = Math.floor(charCode / 10) * (height / 10)
      const size = 20 + (charCode % 40)
      const rotation = charCode * 7

      shapes.push(
        <rect
          key={i}
          x={x}
          y={y}
          width={size}
          height={size}
          fill={fillColor}
          opacity={0.7}
          transform={`rotate(${rotation} ${x + size / 2} ${y + size / 2})`}
        />
      )
    }
    return shapes
  }

  const getShapePath = () => {
    switch (shape) {
      case 'circle':
        return `M ${width / 2}, ${height / 2} m -${width / 2}, 0 a ${width / 2},${height / 2} 0 1,0 ${width},0 a ${width / 2},${height / 2} 0 1,0 -${width},0`
      case 'squircle':
        return `M ${width / 2} 0 C ${width * 0.85} 0 ${width} ${height * 0.15} ${width} ${height / 2} C ${width} ${height * 0.85} ${width * 0.85} ${height} ${width / 2} ${height} C ${width * 0.15} ${height} 0 ${height * 0.85} 0 ${height / 2} C 0 ${height * 0.15} ${width * 0.15} 0 ${width / 2} 0 Z`
      case 'hexagon':
        const hexRadius = Math.min(width, height) / 2
        const hexPoints = []
        for (let i = 0; i < 6; i++) {
          const angle = (i * Math.PI) / 3
          const x = width / 2 + hexRadius * Math.cos(angle)
          const y = height / 2 + hexRadius * Math.sin(angle)
          hexPoints.push(`${x},${y}`)
        }
        return `M ${hexPoints.join(' L ')} Z`
      default:
        return `M 0,0 H ${width} V ${height} H 0 Z`
    }
  }

  const getFontSize = () => {
    const baseSize = Math.min(width, height) / 10
    const textLength = text.length
    return Math.max(12, Math.min(baseSize, 200 / textLength))
  }

  return (
    <svg width={width} height={height} viewBox={`0 0 ${width} ${height}`} className={`bg-primary ${className || ''}`}>
      <defs>
        <clipPath id={uniqueId}>
          <path d={getShapePath()} />
        </clipPath>
      </defs>
      <g clipPath={`url(#${uniqueId})`}>
        <rect width={width} height={height} className="fill-primary" />
        {generateShapes()}
      </g>
      <text
        x="50%"
        y="50%"
        dominantBaseline="middle"
        textAnchor="middle"
        fontSize={getFontSize()}
        fontWeight="bold"
        className={`fill-primary-foreground text-${textSize}`}
      >
        {text}
      </text>
    </svg>
  )
}

