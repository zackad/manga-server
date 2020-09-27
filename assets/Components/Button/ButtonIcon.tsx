import { h } from 'preact'

export function ButtonIcon({ onClick, children, margin }) {
  return (
    <button className={`inline-block ${margin} p-1 w-8 h-8`} onClick={onClick}>
      {children}
    </button>
  )
}
