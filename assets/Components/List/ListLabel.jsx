import { h } from 'preact'

export function ListLabel({ uri, label }) {
  return (
    <a className='inline-flex items-start' href={uri}>
      <span className='font-thin'>{label}</span>
    </a>
  )
}
