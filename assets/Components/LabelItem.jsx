import { h } from 'preact'

function LabelItem({ title }) {
  return <span className='text-secondary'> / {title}</span>
}

export { LabelItem }
