import { h, JSX } from 'preact'

type ButtonIconProps = {
  margin?: string
  onClick?: any
  children?: JSX.Element
}

export function ButtonIcon({ onClick, children, margin }: ButtonIconProps) {
  return (
    <button className={`inline-block ${margin} p-1 w-8 h-8`} onClick={onClick}>
      {children}
    </button>
  )
}
