import { h } from 'preact'

export function ListLabel({ uri, label }) {
  return (
    <a className='inline-flex items-start' href={uri}>
      <span>{label}</span>
    </a>
  )
}
