# 算法
## 排序
### 冒泡排序
- 不断交换前后2个元素，最大的往后一直往后
```go

func bubbleSort(arr []int) {
	for i := 0; i < len(arr)-1; i++ {
		for j := 0; j < len(arr)-i-1; j++ {
			if arr[j] > arr[j+1] {
				arr[j], arr[j+1] = arr[j+1], arr[j]
			}
		}
	}
}

```

### 快速排序
- 计算partition值（假定第一个值为中心点j=start位置的值，从start+1位置开始，与中心值比较，若更小，则与j+1位置的值交换，直到末了；交换start与j位置的值），再分别计算partition位置前、后部分的partition值

```go
func quickSort(arr []int, start, end int) {
	if start >= end {
		return
	}
	pivot := partition(arr, start, end)
	quickSort(arr, start, pivot-1)
	quickSort(arr, pivot+1, end)
}

func partition(arr []int, start, end int) int {
	pivotV := arr[start]
	j := start
	for i := start + 1; i <= end; i++ {
		if arr[i] < pivotV {
			arr[j+1], arr[i] = arr[i], arr[j+1]
			j++
		}
	}
	arr[start], arr[j] = arr[j], arr[start]
	return j
}

```

### 二叉树排序
- 定义二叉树的结构体，将切片按照大小关系构造为一个二叉树，递归分别拿出左边、中间、右边的值

```go
type tree struct {
	value       int
	left, right *tree
}

func treeInsertSort(arr []int) []int {
	var t *tree
	for _, v := range arr {
		t = add(t, v)
	}
	return each(t, arr[:0])
}


func add(t *tree, value int) *tree {
	if t == nil {
		return &tree{
			value: value,
			left:  nil,
			right: nil,
		}
	}
	if value < t.value {
		t.left = add(t.left, value)
	} else {
		t.right = add(t.right, value)
	}
	return t
}
func each(t *tree, valueList []int) []int {
	if t != nil {
		valueList = each(t.left, valueList)
		valueList = append(valueList, t.value)
		valueList = each(t.right, valueList)
	}
	return valueList
}
```

## 红黑树算法

## 二叉树分层遍历

