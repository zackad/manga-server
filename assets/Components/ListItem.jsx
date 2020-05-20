import { h } from 'preact'

import { ArchiveItem } from './List/ArchiveItem'
import { DirectoryItem } from './List/DirectoryItem'
import { FileItem } from './List/FileItem'

export function ListItem({ item }) {
  if (item.type === 'directory') return <DirectoryItem {...item} />
  if (item.type === 'archive') return <ArchiveItem {...item} />
  return <FileItem {...item} />
}
