import { h } from 'preact'

function ListItem({ className, item }) {
  return (
    <p className='p-2'>
      <a className={`${className}`} href={item.uri}>
        {item.label}
      </a>
    </p>
  )
}

export { ListItem }
