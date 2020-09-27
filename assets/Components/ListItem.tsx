import { h } from 'preact'

import { ArchiveItem } from './List/ArchiveItem'
import { DirectoryItem } from './List/DirectoryItem'
import { FileItem } from './List/FileItem'
import { EntryData } from 'App/types/EntryData'

type ListItemProps = {
  item: EntryData
}

export function ListItem({ item }: ListItemProps) {
  if (item.type === 'directory') return <DirectoryItem {...item} />
  if (item.type === 'archive') return <ArchiveItem {...item} />
  return <FileItem {...item} />
}
