import { h } from 'preact'

export function ButtonIcon({ onClick, children }) {
  return (
    <button className='inline-block mx-1 p-1 w-8 h-8' onClick={onClick}>
      {children}
    </button>
  )
}
