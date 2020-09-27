import { h } from 'preact'

function LabelItem({ title }) {
  return <span className='text-secondary whitespace-no-wrap'> / {title}</span>
}

export { LabelItem }
